<?php

use App\Models\School;
use App\Models\SchoolAdmins;
use Illuminate\Support\Facades\Auth;

function getSchoolSettings() {
    return School::where('id', Auth::user()->school_id)->first();
}

function getSchoolAdmins() {
    $admins = SchoolAdmins::where('school_id', Auth::user()->school_id)->get();
    return $admins->pluck('admin_id')->toArray();
}

function getAssessmentDetails($learner_id, $obj) {
    return $obj->where('learner_id', $learner_id)->first();
}
