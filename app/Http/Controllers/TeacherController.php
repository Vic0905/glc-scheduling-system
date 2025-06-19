<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        // eager load the teacher and user relationship
        $schedules = Schedule::with(['teacher', 'teacher.user'])->get();
        // get all teachers from the database using the teacher model class 
        $teachers = Teacher::all();
        // get the search term from the query string 
        $teacherName = $request->query('teacher_name');

        // Query the subjects table, using the correct column name 'teachername'
        $teachers = Teacher::query()
            ->when($teacherName, function ($query) use ($teacherName) {
                $query->where('name', 'like', '%' . $teacherName . '%');
            })
            ->orderBy('name', 'asc') // Sort alphabetically
            ->paginate(20); // Display 15 teachers per page
    
    
        return view('teachers.index', compact('teachers', 'teacherName'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created teacher in storage and create a user record for authentication.
     */
    public function store(Request $request)
    {
        // Validate the input fields 
        $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', // Ensure password confirmation is included
        ]);

        try {
            // Create the user record for authentication 
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign the 'teacher' role to the user 
            $user->assignRole('teacher');
            
            // Create the teacher record with the user_id
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nickname' => $request->nickname,
                'name' => $request->name, // Ensure 'name' is passed here
            ]);

            // Log the activity for teacher creation 
            ActivityLog::create([
                'activity' => 'Teacher ' . $teacher->nickname . ' added',
                'model_type' => 'Teacher',
                'model_id' => $teacher->id,
            ]);

            return redirect()->route('teachers.index')->with('success', 'Teacher added successfully!');
        } catch (\Exception $e) {
            // Log any errors encountered during the process

            return back()->with('error', 'Failed to add teacher');
        }
    }
    



    /**
     * Show the form for editing the specified teacher in storage.
     */
    public function edit($id)
    {
        // find the teacher by id and pass it to the view for editing purpose
        $teacher = Teacher::findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, $id)
    {
        // Find and update the teacher with the request data
        $teacher = Teacher::findOrFail($id);
        $teacher->update($request->all());

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully!');
    }

    

 public function getStudents(Teacher $teacher, $scheduleDate)
{
    try {
        // Get all schedules for this teacher on the specified date
        $schedules = $teacher->schedules()
            ->where('schedule_date', $scheduleDate)
            ->with(['student', 'subject'])
            ->get();

        // Define all time slots to check
        $timeSlots = [
            'time_8_00_8_50',
            'time_9_00_9_50',
            'time_10_00_10_50',
            'time_11_00_11_50',
            'time_12_00_12_50',
            'time_13_00_13_50',
            'time_14_00_14_50',
            'time_15_00_15_50',
            'time_16_00_16_50',
            'time_17_00_17_50'
        ];

        $students = collect();

        // Iterate over each schedule for the teacher
        foreach ($schedules as $schedule) {
            // Check each defined time slot
            foreach ($timeSlots as $timeSlot) {
                if ($schedule->$timeSlot) {
                    // Create a unique key for each student-time slot combination
                    $uniqueKey = $schedule->student->id . '_' . $timeSlot;

            //            //  Log student details before filtering
            // \Log::info('Current Student:', [
            //     'id' => $schedule->student->id,
            //     'schedule_id' => $schedule->id,
            //     'timeSlot' => $timeSlot
            // ]);
            // \Log::info('Total schedules found:', ['count' => $schedules->count()]);


                    if (!$students->contains(fn($value) => isset($value['unique_key']) && $value['unique_key'] === $uniqueKey)) {
                        $students->push([
                            'unique_key' => $uniqueKey,
                            'id' => $schedule->student->id ?? 'N/A',
                            'name' => $schedule->student->name ?? 'N/A',
                            'email' => $schedule->student->email ?? 'N/A',
                            'subject' => $schedule->subject->subjectname ?? 'N/A',
                            'status' => $schedule->status ?? 'N/A',
                            'time' => str_replace('_', ':', $timeSlot)
                        ]);
                    }
                    

                }
            }
        }

        // Remove the unique_key field before returning the response
        $students = $students->map(function ($student) {
            unset($student['unique_key']);
            return $student;
        });

        // // Debugging: Log the students data
        // \Log::info('Students data:', $students->toArray());

        // Return all results
        return response()->json($students); 
        
    } catch (\Exception $e) {
        // Handle any exceptions that might occur
        return response()->json(['error' => $e->getMessage()], 500);
    }
}





    /**
     * Remove the specified teacher from storage.
     */
    public function destroy($id)
    {
        // Find the teacher record
        $teacher = Teacher::findOrFail($id);
    
        // Store the user ID before deleting the teacher
        $userId = $teacher->user_id; 
    
        // Delete the teacher record
        $teacher->delete();
    
        // If the teacher has an associated user, delete the user too
        if ($userId) {
            User::find($userId)?->delete();
        }
    
        return redirect()->route('teachers.index')->with('success', 'Teacher and associated user deleted successfully!');
    }
    
}
