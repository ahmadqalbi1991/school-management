<?php

use App\Models\PerformanceLevel;
use App\Models\School;
use App\Models\SchoolAdmins;
use Illuminate\Support\Facades\Auth;

function getSchoolSettings()
{
    return School::where('id', Auth::user()->school_id)->first();
}

function getSchoolAdmins($id = null)
{
    if ($id === null) {
        $id = Auth::user()->school_id;
    }
    $admins = SchoolAdmins::where('school_id', $id)->get();
    return $admins->pluck('admin_id')->toArray();
}

function getAssessmentDetails($learner_id, $obj)
{
    return $obj->where('learner_id', $learner_id)->first();
}

function initials($str)
{
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
}

function checkPointsCriteria($points, $total_check = false) {
    $initial_point = 0;
    $admins = getSchoolAdmins();
    $levels = PerformanceLevel::when(in_array(Auth::user()->role, ['admin', 'teacher']), function ($q) use ($admins) {
        return $q->whereIn('created_by', $admins);
    })->orderby('points')->get();

    foreach ($levels as $key => $level) {
        if ($points >= $initial_point && $points <= $level->points) {
            return $level->title;
        }

        if (($total_check)) {
            $level_obj = PerformanceLevel::when(in_array(Auth::user()->role, ['admin', 'teacher']), function ($q) use ($admins) {
                return $q->whereIn('created_by', $admins);
            })
                ->orderBy('points', 'desc')
                ->where('points', '<=', $points)
                ->first();

            if ($level_obj) {
                return $level_obj->title;
            }
        }

        $initial_point = $level->points + .1;
    }
}
