<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $teacherName = $request->query('teacher_name', '');
        $studentName = $request->query('student_name', '');
        $date = $request->input('schedule_date', '');

        // Always get Monday to Friday of the current week
        $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endDate = $startDate->copy()->addDays(4); // Friday

        $query = Schedule::with([
            'student',
            'teacher.user',
            'teacher.room',
            'subTeacher.user',
            'subTeacher.room',
            'subject',
            'room',
        ])->where('schedule_state', '!=', 'cleared');

        if ($date) {
            $query->whereDate('schedule_date', $date);
        }

        // Only fetch schedules between Monday to Friday
        $query->whereBetween('schedule_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if ($teacherName) {
            $query->whereHas('teacher.user', function ($q) use ($teacherName) {
                $q->where('name', 'like', '%'.$teacherName.'%');
            });
        }

        if ($studentName) {
            $query->whereHas('student', function ($q) use ($studentName) {
                $q->where('name', 'like', '%'.$studentName.'%');
            });
        }

        $schedules = $query->get();

        // Handle sub teacher schedules
        $subSchedules = $schedules->filter(fn ($s) => $s->sub_teacher_id)->map(function ($s) {
            $clone = clone $s;
            $clone->teacher_id = $s->sub_teacher_id;
            $clone->teacher = $s->subTeacher;
            $clone->room = $s->subTeacher->room ?? $s->room;
            return $clone;
        });
        

        $mergedSchedules = $schedules->merge($subSchedules);

        // Filter by teacher role, and restrict to weekdays
        if ($user->hasRole('teacher')) {
            $mergedSchedules = $mergedSchedules->filter(function ($schedule) use ($user) {
                if (! $schedule->schedule_date || ! $schedule->teacher) {
                    return false;
                }

                $isOwner = $schedule->teacher->user_id === $user->id;
                $dayOfWeek = Carbon::parse($schedule->schedule_date)->dayOfWeek;

                return $isOwner && $dayOfWeek >= 1 && $dayOfWeek <= 5;
            });
        }

        $rooms = Room::orderBy('roomname', 'asc')->get();

        $mergedSchedules = $mergedSchedules->sortBy(function ($schedule) {
            return $schedule->room->roomname ?? '';
        });

        $groupedSchedules = $mergedSchedules->groupBy(function ($schedule) {
            return $schedule->teacher->name.'-'.$schedule->schedule_date;
        });

        return view('schedules.index', compact(
            'groupedSchedules',
            'rooms',
            'teacherName',
            'studentName',
            'date',
            'startDate',
            'endDate'
        ));
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
        $validatedData = $request->validate([
            'schedule_date' => 'required|date',
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:users,id',
            'sub_teacher_id' => 'nullable|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'time_slot' => ['required', 'string'],
            'repeat_week' => 'nullable',
            'room_id' => 'nullable|exists:rooms,id', // Allow room_id from request if needed
        ]);

        // ✅ Attempt to get teacher room_id
        $teacher = Teacher::where('user_id', $validatedData['teacher_id'])->first();

        // ✅ Use teacher's room_id or fallback to room_id from request
        $roomId = $teacher->room_id ?? $validatedData['room_id'] ?? null;

        if (! $roomId) {
            return response()->json([
                'success' => false,
                'message' => 'No room found for the selected teacher or request.',
            ], 422);
        }

        $baseData = array_merge($validatedData, [
            'room_id' => $roomId,
            'status' => 'N/A',
            'schedule_state' => 'active',
        ]);

        $startDate = \Carbon\Carbon::parse($validatedData['schedule_date']);

        $datesToSchedule = [];

        if ($request->has('repeat_week')) {
            $friday = $startDate->copy()->endOfWeek(Carbon::FRIDAY);
            for ($date = $startDate->copy(); $date->lte($friday); $date->addDay()) {
                if ($date->isWeekend()) continue;
                $datesToSchedule[] = $date->copy();
            }
        } else {
            $datesToSchedule[] = $startDate;
        }

        $createdSchedules = [];
        $skippedDates = [];

        foreach ($datesToSchedule as $date) {
            $conflict = Schedule::where('schedule_date', $date->toDateString())
                ->where('time_slot', $validatedData['time_slot'])
                ->where(function ($query) use ($request) {
                    $query->where('teacher_id', $request->teacher_id)
                        ->orWhere('student_id', $request->student_id);
                })->exists();

            if ($conflict) {
                $skippedDates[] = $date->format('l (Y-m-d)');
                continue;
            }

            $baseData['schedule_date'] = $date->toDateString();
            $createdSchedules[] = Schedule::create($baseData);
        }

        return response()->json([
            'success' => true,
            'message' => count($createdSchedules).' schedule(s) created.',
            'skipped' => $skippedDates,
            'data' => $createdSchedules,
        ]);
    }

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
                'message' => 'Error deleting schedule: '.$e->getMessage(),
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
                'teacher' => (object) ['name' => 'N/A'],
            ]);
        }

        $teacher = optional($students->first()->teacher)->user ?? (object) ['name' => 'N/A'];

        return view('partials.teacher-students-modal', compact('students', 'teacher'));
    }

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
            'activity' => 'Status updated for Student '.$schedule->student->name.
                        ' in Student room '.$schedule->room->roomname.
                        ' with Teacher '.$schedule->teacher->name.
                        ' for Subject '.$schedule->subject->subjectname.
                        ' with Status '.$schedule->status.
                        ' in Room '.$schedule->room->roomname,  // Assuming 'name' is the column for room name
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
        /** @var \App\Models\User $user */
        if ($user && $user->hasRole('teacher')) {
            $query->where('teacher_id', $user->id);
        }

        // Filter by teacher name (if provided)
        if ($teacherName) {
            $query->whereHas('teacher', function ($q) use ($teacherName) {
                $q->where('name', 'like', '%'.$teacherName.'%');
            });
        }

        // Filter by student name (if provided) at the database level
        if ($studentName) {
            $query->whereHas('student', function ($q) use ($studentName) {
                $q->where('name', 'like', '%'.$studentName.'%');
            });
        }

        $schedules = $query->paginate(20);

        $groupedSchedules = $schedules->groupBy(function ($schedule) {
            return $schedule->teacher_id.'_'.$schedule->room_id.'_'.$schedule->schedule_date;
        });

        return view('schedules.show', compact('groupedSchedules', 'studentName', 'teacherName', 'schedules', 'date'));
    }

    public function input(Request $request)
    { 
        $user = Auth::user();

        // Static dropdowns
        $students = Student::select('id', 'name')->get();
        $teachers = Teacher::with('user:id,name')->get();
        $subjects = Subject::select('id', 'subjectname')->get();

        $teacherName = $request->query('teacher_name', '');
        $studentName = $request->query('student_name', '');
        $startDate = $request->query('start_date', now()->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));

        // Initial schedule filter for determining matching teacher/room IDs
        $filteredSchedulesQuery = Schedule::query()
            ->with('teacher.user')
            ->where('schedule_state', '!=', 'cleared')
            ->whereBetween('schedule_date', [$startDate, $endDate]);

        if ($teacherName) {
            $filteredSchedulesQuery->whereHas('teacher.user', fn ($q) =>
                $q->where('name', 'like', "%$teacherName%")
            );
        }

        if ($studentName) {
            $filteredSchedulesQuery->whereHas('student', fn ($q) =>
                $q->where('name', 'like', "%$studentName%")
            );
        }

        $filteredSchedules = $filteredSchedulesQuery->get();

        // Get matching room IDs
        $roomIdsInFilteredSchedules = $filteredSchedules->pluck('room_id')->unique()->filter()->values();

        // Get paginated rooms
        $rooms = Room::orderBy('roomname')
            ->when($teacherName, function ($query) use ($roomIdsInFilteredSchedules) {
                return $roomIdsInFilteredSchedules->isNotEmpty()
                    ? $query->whereIn('id', $roomIdsInFilteredSchedules)
                    : $query->whereRaw('1 = 0'); // Return none if no match
            })
            ->paginate(30);

        $currentRoomIds = $rooms->pluck('id');

        // Reload actual schedules for current paginated rooms
        $schedules = Schedule::with([
                'student:id,name',
                'teacher.user:id,name',
                'subTeacher.user:id,name',
                'subTeacher.room:id,roomname',
                'subject:id,subjectname',
                'room:id,roomname'
            ])
            ->where('schedule_state', '!=', 'cleared')
            ->whereBetween('schedule_date', [$startDate, $endDate])
            ->whereIn('room_id', $currentRoomIds)
            ->get();

        $schedulesByRoom = $schedules->groupBy(fn ($s) => $s->room->roomname ?? 'Unknown Room');

        return view('schedules.input', compact(
            'schedulesByRoom',
            'schedules',
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
                'activity' => 'Deleted schedule for Student '.($schedule->student->name ?? 'N/A').
                              ' in Room '.($schedule->room->roomname ?? 'N/A').
                              ' with Teacher '.($schedule->teacher->name ?? 'N/A').
                              ' for Subject '.($schedule->subject->subjectname ?? 'N/A').
                              ' with schedule date of '.$schedule->schedule_date ?? 'N/A',
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
 