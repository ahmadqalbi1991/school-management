<?php

use App\Models\School;
use App\Models\SchoolAdmins;
use App\Models\StudentAssessment;
use Illuminate\Support\Facades\Auth;

function getSchoolSettings()
{
    return School::where('id', Auth::user()->school_id)->first();
}

function getSchoolAdmins()
{
    $admins = SchoolAdmins::where('school_id', Auth::user()->school_id)->get();
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

function checkAssessment(
    $level = null,
    $strand_id = null,
    $sub_strand_id = null,
    $activity_id = null,
    $learner_id = null,
    $subject_id = null,
    $stream_id = null,
    $term_id = null
)
{
    return StudentAssessment::when($level, function ($q) use ($level) {
        return $q->where('performance_level_id', $level);
    })
        ->when($strand_id, function ($q) use ($strand_id) {
            return $q->where('strand_id', $strand_id);
        })
        ->when($sub_strand_id, function ($q) use ($sub_strand_id) {
            return $q->where('sub_strand_id', $sub_strand_id);
        })
        ->when($activity_id, function ($q) use ($activity_id) {
            return $q->where('learning_activity_id', $activity_id);
        })
        ->when($learner_id, function ($q) use ($learner_id) {
            return $q->where('learner_id', $learner_id);
        })
        ->when($subject_id, function ($q) use ($subject_id) {
            return $q->where('subject_id', $subject_id);
        })
        ->when($stream_id, function ($q) use ($stream_id) {
            return $q->where('stream_id', $stream_id);
        })
        ->when($term_id, function ($q) use ($term_id) {
            return $q->where('term_id', $term_id);
        })
        ->with('level')
        ->first();
}
