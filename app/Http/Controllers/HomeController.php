<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\StudentAssessment;
use App\Models\Subjects;
use App\Models\SummativeAssessment;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role === 'super_admin') {
            $total_schools = School::get();
            $new_schools = School::latest()->limit(10)->get();
            $users = User::all();
            $subjects = Subjects::all();
            $teachers = User::where('role', 'teacher')->with('school')->get();

            return view('dashboard.super-admin', compact('total_schools', 'teachers', 'users', 'subjects', 'new_schools'));
        }

        if (Auth::user()->role === 'admin' || Auth::user()->role === 'teacher') {
            $users = User::where('school_id', Auth::user()->school_id)->get();
            $subjects = Subjects::all();
            $teachers = User::where('role', 'teacher')->with('school')->get();
            $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();
            $total_classes = [];
            if ($classes->count()) {
                $total_classes = $classes->pluck('id')->toArray();
            }
            $formative_assessments = StudentAssessment::whereIn('class_id', $total_classes)->get()->groupBy('subject_id');
            $summative_assessments = SummativeAssessment::whereIn('class_id', $total_classes)->get()->groupBy('subject_id');

            return view('dashboard.admin', compact( 'teachers', 'users', 'subjects', 'formative_assessments', 'summative_assessments'));
        }
    }

    public function clearCache(): View
    {
        Artisan::call('cache:clear');

        return view('clear-cache');
    }
}
