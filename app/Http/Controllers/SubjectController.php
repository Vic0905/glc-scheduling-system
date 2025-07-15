<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    { 
        // get all subjects from the database using the subject model class 
        $subjectName = $request->query('subject_name');
        
        // Query the subjects table, using the correct column name 'subjectname'
        $subjects = Subject::query()
            ->when($subjectName, function ($query) use ($subjectName) {
                $query->where('subjectname', 'like', '%' . $subjectName . '%');
            })
            ->orderBy('subjectname', 'asc')
            ->paginate(10);    

        return view('subjects.index', compact('subjects', 'subjectName'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        // Create the subject with the request data
        $subject = Subject::create($request->all());

        // Log the activity for creation of the subject 
        ActivityLog::create([
            'activity' => 'Subject ' . $subject->name . ' created',
            'model_type' => 'Subject',
            'model_id' => $subject->id,
        ]);

        // Redirect to the subjects list with success message 
        return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }

    public function edit($id)
    {
        // find the subject by id and pass it to the view for editing purpose
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        //  find the subject by id and update it with the request data 
        $subject = Subject::findOrFail($id);
        $subject->update($request->all());

        // Log the activity for update (optional)
        ActivityLog::create([
            'activity' => 'Subject ' . $subject->name . ' updated',
            'model_type' => 'Subject',
            'model_id' => $subject->id,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy($id)
    {
        // find the subjec by id and delete it from the database
        $subject = Subject::findOrFail($id);
        $subject->delete();

        // Log the activity for deletion (optional)
        ActivityLog::create([
            'activity' => 'Subject ' . $subject->name . ' deleted',
            'model_type' => 'Subject',
            'model_id' => $subject->id,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
