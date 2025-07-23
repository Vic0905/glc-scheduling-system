<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $studentName = $request->query('student_name');

        // If a student name is provided, filter students by that name
        $students = Student::query() 
            ->when($studentName, function ($query) use ($studentName) {
                $query->where('name', 'like', '%'.$studentName.'%');
            })
            ->orderBy('name', 'asc')
            ->get();

        $studentCount = $students->count();

        return view('students.index', compact('students', 'studentName', 'studentCount'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        // Create the student with the request data
        $student = Student::create($request->all());

        // Log the activity for creation
        ActivityLog::create([
            'activity' => 'Student '.$student->name.' enrolled',
            'model_type' => 'Student',
            'model_id' => $student->id,
        ]);

        // Redirect to the students list with success message
        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function edit($id)
    {
        // find the student by id and pass it to the view for editing purpose
        $student = Student::findOrFail($id);

        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        // find the student by id and update it with the request data
        $student = Student::findOrFail($id);
        $student->update($request->all());

        // Log the activity for update (optional)
        ActivityLog::create([
            'activity' => 'Student '.$student->name.' updated',
            'model_type' => 'Student',
            'model_id' => $student->id,
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        // find the student by id and delete it from the database
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
