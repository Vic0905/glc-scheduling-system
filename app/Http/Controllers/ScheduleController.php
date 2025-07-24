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

        $startDate = $request->input('start_date') ?? now()->toDateString();
        $endDate = $request->input('end_date') ?? now()->toDateString();

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

        if ($startDate && $endDate) {
            $query->whereBetween('schedule_date', [$startDate, $endDate]);
        }

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

        // Duplicate sub teacher schedule
        $subSchedules = $schedules->filter(fn ($s) => $s->sub_teacher_id)->map(function ($s) {
            $clone = clone $s;
            $clone->teacher_id = $s->sub_teacher_id;
            $clone->teacher = $s->subTeacher;
            $clone->room = $s->subTeacher->room ?? $s->room;

            return $clone;
        });

        $mergedSchedules = $schedules->merge($subSchedules);

        // ✅ Only show schedules where teacher is current user and weekday only (Mon-Fri)
        /** @var \App\Models\User $user */
        if ($user->hasRole('teacher')) {
            $mergedSchedules = $mergedSchedules->filter(function ($schedule) use ($user) {
                if (! $schedule->schedule_date || ! $schedule->teacher) {
                    return false;
                }

                $isOwner = $schedule->teacher->user_id === $user->id;
                $dayOfWeek = \Carbon\Carbon::parse($schedule->schedule_date)->dayOfWeek;

                return $isOwner && $dayOfWeek >= 1 && $dayOfWeek <= 5; // Monday to Friday
            });
        }

        // Get all rooms sorted by room name
        $rooms = Room::orderBy('roomname', 'asc')->get(); // ✅ Define $rooms

        // Sort mergedSchedules by room name
        $mergedSchedules = $mergedSchedules->sortBy(function ($schedule) {
            return $schedule->room->roomname ?? '';
        });

        // Group by teacher name + date
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
        // This will validate a the records that are inputted this will ensure if the
        $validatedData = $request->validate([
            'schedule_date' => 'required|date',
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:users,id',
            'sub_teacher_id' => 'nullable|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_id' => 'required|exists:rooms,id',
            'schedule_time' => ['required', Rule::in(array_keys($timeSlots))],
            'repeat_week' => 'nullable', // checkbox this can be null to ensure it can create schedule even if individual or bulking
        ]);

        $timeSlotColumn = $timeSlots[$request->schedule_time];
        $baseData = array_merge($validatedData, [
            $timeSlotColumn => 1,
            'status' => 'N/A',
            'schedule_state' => 'active',
        ]);

        $startDate = \Carbon\Carbon::parse($validatedData['schedule_date']);

        $datesToSchedule = [];

        if ($request->has('repeat_week')) {
            // Get the upcoming Friday of the same week
            $friday = $startDate->copy()->endOfWeek(Carbon::FRIDAY);
            for ($date = $startDate->copy(); $date->lte($friday); $date->addDay()) {
                if ($date->isWeekend()) {
                    continue;
                } // skip Saturday & Sunday
                $datesToSchedule[] = $date->copy();
            }
        } else {
            $datesToSchedule[] = $startDate;
        }

        $createdSchedules = [];
        $skippedDates = [];

        foreach ($datesToSchedule as $date) {
            $baseData['schedule_date'] = $date->toDateString();

            $conflict = Schedule::where('schedule_date', $date->toDateString())
                ->where(function ($query) use ($request, $timeSlotColumn) {
                    $query->where(function ($q) use ($request, $timeSlotColumn) {
                        $q->where('teacher_id', $request->teacher_id)
                            ->where($timeSlotColumn, 1);
                    })->orWhere(function ($q) use ($request, $timeSlotColumn) {
                        $q->where('student_id', $request->student_id)
                            ->where($timeSlotColumn, 1);
                    });
                })->exists();

            if ($conflict) {
                $skippedDates[] = $date->format('l (Y-m-d)');

                continue;
            }

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
                $q->where('name', 'like', '%'.$teacherName.'%');
            });
        }

        if ($studentName) {
            $query->whereHas('student', function ($q) use ($studentName) {
                $q->where('name', 'like', '%'.$studentName.'%');
            });
        }

        $schedules = $query->get();

        $teacherIdsInFilteredSchedules = $schedules->pluck('teacher_id')->unique()->filter()->values()->all();

        $roomQuery = Room::orderBy('roomname');

        if ($teacherName && ! empty($teacherIdsInFilteredSchedules)) {
            $roomIdsInFilteredSchedules = $schedules->pluck('room_id')->unique()->filter()->values()->all();
            $roomQuery->whereIn('id', $roomIdsInFilteredSchedules);
        } elseif ($teacherName && empty($teacherIdsInFilteredSchedules)) {
            $roomQuery->whereRaw('1 = 0'); // No rooms shown if no match
        }

        $rooms = $roomQuery->paginate(30);

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
