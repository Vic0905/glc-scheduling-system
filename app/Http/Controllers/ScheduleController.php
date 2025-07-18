<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\Room;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource. | the method below will enable the function to display the data
     */
    // This method is commented out because it was used for card design code.
    // public function index(Request $request)
    // {
    //     $user = Auth::user(); // Get the authenticated user 
        
    //     // Retrieve filter parameters
    //     $teacherName = $request->query('teacher_name', '');
    //     $studentName = $request->query('student_name', '');
    //     $date = $request->input('date', '');
        
    //     // Base query with eager loaded relationships
    //     $query = Schedule::with(['student', 'teacher', 'subject', 'room'])
    //                      ->orderBy('schedule_date', 'asc') // newest schedules first
    //                      ->orderBy('created_at', 'asc'); // newest schedules first
        
    //     // Apply date filter only if a date is selected 
    //     if (!empty($date)) {
    //         $query->whereDate('schedule_date', $date);
    //     }
        
    //     // If the user is a teacher, filter schedules by their teacher_id (user_id)
    //     if ($user && $user->hasRole('teacher')) {
    //         $query->where('teacher_id', $user->id);
    //     }
        
    //     // Filter by teacher name (if provided)
    //     if ($teacherName) {
    //         $query->whereHas('teacher', function ($q) use ($teacherName) {
    //             // Assuming 'name' column exists directly on the users table for teachers
    //             $q->where('name', 'like', '%' . $teacherName . '%');
    //         });
    //     }
        
    //     // Filter by student name (if provided) at the database level
    //     if ($studentName) {
    //         $query->whereHas('student', function ($q) use ($studentName) {
    //             $q->where('name', 'like', '%' . $studentName . '%');
    //         });
    //     }
        
    //     // Fetch all schedules for grouping
    //     $allSchedules = $query->get(); 
        
    //     // Group schedules by roomname first, then by teacher_id and schedule_date
    //     // This structure matches what my Blade template expects ($schedulesByRoom[$room->roomname])
    //     $schedulesByRoom = $allSchedules->groupBy('room.roomname')->map(function ($roomSchedules) {
    //         return $roomSchedules->groupBy(function ($schedule) {
    //             return $schedule->teacher_id . '_' . $schedule->schedule_date;
    //         });
    //     });

    //     // Fetch additional data required by the Blade template
    //     $rooms = Room::orderBy('roomname')->paginate(10); // Adjust pagination as needed, or use ->get() if not paginating rooms
    //     $teachers = User::role('teacher')->orderBy('name')->get(); // Assuming teachers are users with 'teacher' role
    //     $students = Student::orderBy('name')->get();
    //     $subjects = Subject::orderBy('subjectname')->get();

    //     return view('schedules.index', compact(
    //         'schedulesByRoom', // This is the main data for your table
    //         'rooms',          // Needed for iterating through rooms
    //         'teachers',       // Needed for teacher dropdown
    //         'students',       // Needed for student dropdown
    //         'subjects',       // Needed for subject dropdown
    //         'studentName',
    //         'teacherName',
    //         'date'
    //     ));
    // }

    //This method show the table design not card design 
  public function index(Request $request)
{
    $user = Auth::user(); 
    
    $teacherName = $request->query('teacher_name', '');
    $studentName = $request->query('student_name', '');
    $date = $request->input('schedule_date', '');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Base query with eager loaded relationships
    $query = Schedule::with(['student', 'teacher.user', 'subTeacher.room', 'subject', 'room'])
                     ->orderBy('room_id', 'asc') // Order by room_id first
                     ->orderBy('schedule_date', 'desc')
                     ->orderBy('created_at', 'asc');

    // Date range filter takes priority if both are present
    if (!empty($startDate) && !empty($endDate)) {
        $query->whereBetween('schedule_date', [$startDate, $endDate]);
    } elseif (!empty($date)) {
        // Fallback to single date filter if date range is not used
        $query->whereDate('schedule_date', $date);
    }

    // Filter for teachers (if role is teacher)
    if ($user && $user->hasRole('teacher')) {
        $query->where('teacher_id', $user->id);
    }

    // Filter by teacher name
    if (!empty($teacherName)) {
        $query->whereHas('teacher.user', function ($q) use ($teacherName) {
            $q->where('name', 'like', '%' . $teacherName . '%');
        });
    }

    // Filter by student name
    if (!empty($studentName)) {
        $query->whereHas('student', function ($q) use ($studentName) {
            $q->where('name', 'like', '%' . $studentName . '%');
        });
    }

    // Get all matching schedules
    $schedules = $query->get();

    // Group by teacher_id, room_id, and schedule_date
    $groupedSchedules = $schedules->groupBy(function ($item) {
        return $item->teacher_id . '-' . $item->room_id . '-' . $item->schedule_date;
    });

    return view('schedules.index', [
        'schedules' => $schedules,
        'groupedSchedules' => $groupedSchedules,
        'teacherName' => $teacherName,
        'studentName' => $studentName,
        'date' => $date,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ]);
}

public function clear(Request $request, Schedule $schedule)
{
    $schedule->update(['schedule_state' => 'cleared']);

 return response()->json([
    'success' => true,
    'room_id' => $schedule->room_id,
    'time_slot' => $schedule->schedule_time, 
    'slot_key' => $request->slot_key, // passed from form or route
    'start_date' => $request->start_date,
    'end_date' => $request->end_date,
    'students' => Student::select('id', 'name')->orderBy('name')->get(),
    'subjects' => Subject::select('id', 'subjectname')->orderBy('subjectname')->get(),
]);

}
    public function create()
    {
        // list of datas and display it in the admin page for the admin to create the schedule 
        $students = Student::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();
        $rooms = Room::all();

        return view('schedules.create', compact('students', 'teachers', 'subjects', 'rooms'));
    }

   public function store(Request $request)
{
    // Validate input data 
    $validatedData = $request->validate([
        'schedule_date' => 'required|date',
        'student_id' => 'required|exists:students,id',
        'teacher_id' => 'required|exists:users,id',
        'sub_teacher_id' => 'nullable|exists:users,id',
        'subject_id' => 'required|exists:subjects,id',
        'room_id' => 'required|exists:rooms,id',
        'schedule_time' => 'required|in:08:00,09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00',
    ]);

    // Map time slot to column name 
    $timeSlots = [
        '08:00' => 'time_8_00_8_50',
        '09:00' => 'time_9_00_9_50',
        '10:00' => 'time_10_00_10_50',
        '11:00' => 'time_11_00_11_50',
        '12:00' => 'time_12_00_12_50',
        '13:00' => 'time_13_00_13_50',
        '14:00' => 'time_14_00_14_50',
        '15:00' => 'time_15_00_15_50',
        '16:00' => 'time_16_00_16_50',
        '17:00' => 'time_17_00_17_50',
    ];
    
    $timeSlotColumn = $timeSlots[$request->schedule_time];
    $validatedData[$timeSlotColumn] = 1;

    $validatedData['status'] = 'N/A';
    $validatedData['schedule_state'] = 'active';

    // 🔒 Check for teacher conflict
    $teacherConflict = Schedule::where('teacher_id', $request->teacher_id)
        ->where('schedule_date', $request->schedule_date)
        ->where($timeSlotColumn, 1)
        ->exists();

    // 🔒 Check for student conflict
    $studentConflict = Schedule::where('student_id', $request->student_id)
        ->where('schedule_date', $request->schedule_date)
        ->where($timeSlotColumn, 1)
        ->exists();

    if ($teacherConflict || $studentConflict) {
        return response()->json([
            'success' => false,
            'message' => 'Conflict detected: The teacher or student is already scheduled at this time.'
        ], 409); // 409 Conflict HTTP status
    }

    // ✅ Create the schedule
    $schedule = Schedule::create($validatedData);

    return response()->json([
        'success' => true,
        'message' => 'Schedule created successfully!'
    ]);
}


    //  A delete method for individual schedules
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();

            // Check if it's an AJAX request
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Schedule deleted successfully.']);
            }

            // Fallback for normal form delete
            return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting schedule: ' . $e->getMessage()
            ], 500);
        }
    }

     public function edit($id)
     {
         $schedule = Schedule::findOrFail($id);
         $students = Student::all(); // Fetch all students
         $teachers = Teacher::all();
         $subjects = Subject::all();
         $rooms = Room::all();
     
         return view('schedules.edit', compact('schedule', 'students', 'teachers', 'subjects', 'rooms'));
     }
      
     public function update(Request $request, $id)
     {
         // Validate request data
         $request->validate([
             'student_id' => 'required|exists:students,id',
             'student_room_id' => 'required|exists:rooms,id',
             'teacher_id' => 'required|exists:users,id', // Since teacher_id is actually user_id
             'subject_id' => 'required|exists:subjects,id',
             'room_id' => 'required|exists:rooms,id',
            //  'start_date' => 'required|date',
            //  'end_date' => 'required|date|after_or_equal:start_date',
            //  'schedule_time' => 'required',
             'status' => 'required|in:N/A,present MTM,present GRP,absent MTM,absent GRP',
         ]);
     
         // Find the schedule by ID
         $schedule = Schedule::findOrFail($id);
     
         // Update schedule with new data
         $schedule->update([
             'student_id' => $request->student_id,
             'student_room_id' => $request->student_room_id,
             'teacher_id' => $request->teacher_id, // This corresponds to user_id
             'subject_id' => $request->subject_id,
             'room_id' => $request->room_id,
            //  'start_date' => $request->start_date,
            //  'end_date' => $request->end_date,
            //  'schedule_time' => $request->schedule_time,
             'status' => $request->status,
         ]);
     
         // Redirect with success message
         return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
     }
     
    // this method will enable the function to show the modal for the teacher students 
    public function showTeacherStudents($teacherId, $scheduleDate)
    {
        $students = Schedule::where('teacher_id', $teacherId)
            ->where('schedule_date', $scheduleDate)
            ->with(['student', 'subject', 'teacher.user']) // Eager load teacher
            ->orderBy('schedule_time', 'asc')
            ->get();

        if ($students->isEmpty()) {
            return view('partials.teacher-students-modal', [
                'students' => collect(), 
                'teacher' => (object)['name' => 'N/A']
            ]);
        }

        $teacher = optional($students->first()->teacher)->user ?? (object)['name' => 'N/A'];

        return view('partials.teacher-students-modal', compact('students', 'teacher'));
    }

    // this method will enable the function to update the status of the students in the modal
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:N/A,present GRP,absent GRP,present MTM,absent MTM',
        ]);
    
        $schedule = Schedule::findOrFail($id);
        $schedule->status = $request->status;
        $schedule->save();
    
        // Check if it's an AJAX request
        if ($request->ajax()) {
            return response()->json(['message' => 'Status updated successfully!', 'status' => $schedule->status]);
        }

        // Log the activity for schedule update so that admin can know what was updated
        ActivityLog::create([
            'activity' => 'Status updated for Student ' . $schedule->student->name .
                        ' in Student room ' . $schedule->room->roomname .
                        ' with Teacher ' . $schedule->teacher->name . 
                        ' for Subject ' . $schedule->subject->subjectname .
                        ' with Status ' . $schedule->status . 
                        ' in Room ' . $schedule->room->roomname,  // Assuming 'name' is the column for room name 
            'model_type' => 'Schedule',
            'model_id' => $schedule->id,
        ]);
    
        return redirect()->back()->with('success', 'Status updated successfully.');
    }
    
    
    public function Available(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user 
    
        // Retrieve filter parameters
        $teacherName = $request->query('teacher_name', '');
        $studentName = $request->query('student_name', '');
        $date = $request->input('date', '');
    
        // Base query with relationships
        $query = Schedule::with(['student', 'teacher', 'subject', 'room'])
                            ->join('rooms', 'schedules.room_id', '=', 'rooms.id');
    
        // Apply date filter only if a date is selected 
            $query->whereDate('schedule_date', Carbon::today());
       
        // If the user is a teacher, filter schedules by their teacher_id
        if ($user && $user->hasRole('teacher')) {
            $query->where('teacher_id', $user->id);
        }
    
        // Filter by teacher name (if provided) 
        if ($teacherName) {
            $query->whereHas('teacher', function ($q) use ($teacherName) {
                $q->where('name', 'like', '%' . $teacherName . '%');
            });
        }
    
        // Filter by student name (if provided) at the database level
        if ($studentName) {
            $query->whereHas('student', function ($q) use ($studentName) {
                $q->where('name', 'like', '%' . $studentName . '%');
            });
        }
    
        $schedules = $query->paginate(20); 
    
        $groupedSchedules = $schedules->groupBy(function ($schedule) {
            return $schedule->teacher_id . '_' . $schedule->room_id . '_' . $schedule->schedule_date;
        });
    
        return view('schedules.show', compact('groupedSchedules', 'studentName', 'teacherName', 'schedules', 'date'));
    }
    
      public function input(Request $request)
{
    $user = Auth::user();

    $students = Student::all();
    $teachers = Teacher::all(); // For both teacher and substitute dropdowns
    $subjects = Subject::all();

    $teacherName = $request->query('teacher_name', '');
    $studentName = $request->query('student_name', '');
    $startDate = $request->query('start_date', now()->format('Y-m-d'));
    $endDate = $request->query('end_date', now()->format('Y-m-d'));

    // ✅ Include subTeacher eager loading
    $query = Schedule::with(['student', 
                             'teacher.user', 
                             'subTeacher.user',
                             'subTeacher.room', 
                             'subject', 
                             'room'])
        ->where('schedule_state', '!=', 'cleared')
        ->whereBetween('schedule_date', [$startDate, $endDate])
        ->orderBy('created_at', 'desc');

    if ($teacherName) {
        $query->whereHas('teacher.user', function ($q) use ($teacherName) {
            $q->where('name', 'like', '%' . $teacherName . '%');
        });
    }

    if ($studentName) {
        $query->whereHas('student', function ($q) use ($studentName) {
            $q->where('name', 'like', '%' . $studentName . '%');
        });
    }

    $schedules = $query->get();

    $teacherIdsInFilteredSchedules = $schedules->pluck('teacher_id')->unique()->filter()->values()->all();

    $roomQuery = Room::orderBy('roomname');

    if ($teacherName && !empty($teacherIdsInFilteredSchedules)) {
        $roomIdsInFilteredSchedules = $schedules->pluck('room_id')->unique()->filter()->values()->all();
        $roomQuery->whereIn('id', $roomIdsInFilteredSchedules);
    } else if ($teacherName && empty($teacherIdsInFilteredSchedules)) {
        $roomQuery->whereRaw('1 = 0'); // No rooms shown if no match
    }

    $rooms = $roomQuery->paginate(50);

    // Group by room for Blade view
    $schedulesByRoom = $schedules->groupBy(function ($schedule) {
        return $schedule->room->roomname ?? 'Unknown Room';
    });

    return view('schedules.input', compact(
        'schedulesByRoom',
        'rooms',
        'teacherName',
        'studentName',
        'students',
        'teachers',
        'subjects',
        'startDate',
        'endDate'
    ));
}

    
    // delete method to delete the row by room and specific date
    public function destroyByRoomAndDate($roomId, $scheduleDate)
    {
        // Find schedules matching the room and date
        $schedules = Schedule::where('room_id', $roomId)
                             ->where('schedule_date', $scheduleDate)
                             ->get();
    
        if ($schedules->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No schedules found for this room on this date.']);
        }
    
        // Log and delete schedules 
        foreach ($schedules as $schedule) {
            ActivityLog::create([
                'activity' => 'Deleted schedule for Student ' . ($schedule->student->name ?? 'N/A') .
                              ' in Room ' . ($schedule->room->roomname ?? 'N/A') .
                              ' with Teacher ' . ($schedule->teacher->name ?? 'N/A') .
                              ' for Subject ' . ($schedule->subject->subjectname ?? 'N/A').
                              ' with schedule date of ' . $schedule->schedule_date ?? 'N/A',
                'model_type' => 'Schedule',
                'model_id' => $schedule->id,
            ]);
        }
    
        // Delete all matching schedules with the same room and date so that the admin can know what was deleted
        Schedule::where('room_id', $roomId)
                ->where('schedule_date', $scheduleDate)
                ->delete();
    
        return response()->json(['success' => true, 'message' => 'Schedules deleted successfully.']);
    }
    
}