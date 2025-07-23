<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts of students, teachers, and subjects in the database
        $studentsCount = Student::count();
        $teachersCount = Teacher::count();
        $subjectsCount = Subject::count();
        $schedulesCount = Schedule::count();

        // Pass data to the view to display the dashboard
        return view('dashboard', compact('studentsCount', 'teachersCount', 'subjectsCount', 'schedulesCount'));
    }
}
