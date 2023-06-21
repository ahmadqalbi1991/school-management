<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\Learner;
use App\Models\PerformanceLevel;
use App\Models\SchoolClass;
use App\Models\Strand;
use App\Models\Stream;
use App\Models\StudentAssessment;
use App\Models\Subjects;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class FormativeAssessmentController extends Controller
{
    /**
     * @param $class_slug
     * @param $stream_slug
     * @param $subject_slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|void
     */
    public function index($class_slug = null, $stream_slug = null, $subject_slug = null)
    {
        try {
            if (!$stream_slug && !$class_slug) {
                $streams = Stream::where('school_id', Auth::user()->school_id)
                    ->with('school_class')
                    ->get();
                return view('formative-assessments.streams', compact('streams'));
            }

            if ($class_slug && $stream_slug && !$subject_slug) {
                $classObj = SchoolClass::where('slug', $class_slug)->first();
                $subjects = ClassSubject::where('class_id', $classObj->id)
                    ->with('subject')
                    ->get();

                return view('formative-assessments.subjects', compact('subjects', 'class_slug', 'stream_slug'));
            }

            if ($class_slug && $stream_slug && $subject_slug) {
                $subject = Subjects::where('slug', $subject_slug)
                    ->with('terms', function ($q) {
                        return $q->with('term');
                    })
                    ->with('strands')
                    ->first();
                $admins = getSchoolAdmins();
                $class = SchoolClass::where('slug', $class_slug)->first();
                $stream = Stream::where([
                    'slug' => $stream_slug,
                    'class_id' => $class->id
                ])->first();
                $learners = User::where([
                    'school_id' => Auth::user()->school_id,
                    'stream_id' => $stream->id
                ])->get();
                $levels = PerformanceLevel::when(in_array(Auth::user()->role, ['admin', 'teacher']), function ($q) use ($admins) {
                    return $q->whereIn('created_by', $admins);
                })->latest()->get();
                $terms = $subject->terms;
                $strands = $subject->strands;

                return view('formative-assessments.assessment', compact('terms', 'strands', 'levels', 'learners', 'class', 'stream', 'subject'));
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        try {
            $input = $request->except('_token');
            $assessments = $input['assessments'];
            unset($input['assessments']);
            StudentAssessment::where($input)->delete();
            $subject_assessment = [];
            $i = 0;
            foreach ($assessments as $key => $assessment) {
                $subject_assessment[$i] = $input;
                $subject_assessment[$i]['learner_id'] = $key;
                $subject_assessment[$i]['performance_level_id'] = $assessment;
                $subject_assessment[$i]['created_at'] = Carbon::now();
                $subject_assessment[$i]['updated_at'] = Carbon::now();
                $i++;
            }

            StudentAssessment::insert($subject_assessment);

            return redirect()->back()->with('success', 'Assessment Created');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function getAssessments(Request $request)
    {
        try {
            $input = $request->all();
            $assessments = StudentAssessment::where($input['data'])->get();
            $return_object = [];
            foreach ($assessments as $assessment) {
                $return_object[$assessment->learner_id] = $assessment->performance_level_id;
            }

            return $return_object;
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function reports()
    {
        try {
            $classes = SchoolClass::where(['status' => 1])->get();
            $terms = Term::all();

            return view('formative-assessments.reports', compact('classes', 'terms'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLearners(Request $request)
    {
        try {
            $data = User::where([
                'role' => 'learner',
                'status' => 'active',
                'stream_id' => $request->stream_id
            ])
                ->with('assessments', function ($q) use ($request) {
                    return $q->where('term_id', $request->term_id);
                })
                ->whereHas('assessments')
                ->get();
            $hasManagePermission = Auth::user()->can('manage_formative_assessments');

            return Datatables::of($data)
                ->addColumn('action', function ($data) use ($hasManagePermission, $request) {
                    $output = '';
                    if ($hasManagePermission) {
                        $output = '<div class="">
                                    <a href=""><i class="fas fa-file-pdf f-16 text-pink"></i></a>
                                    <a href=""><i class="fas fa-envelope f-16 text-blue"></i></a>
                                    <a href="' . route('reports.view-result', [
                                        'learner_id' => $data->id, 'stream_id' => $request->stream_id, 'term_id' => $request->term_id]) . '">
                                    <i class="ik ik-eye f-16 text-green"></i>
                                    </a>
                                </div>';
                    }

                    return $output;
                })
                ->make(true);
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function viewResult($learner_id, $stream_id, $term_id) {
        try {
            $stream = Stream::where('id', $stream_id)
                ->with('school_class', function ($q) {
                    return $q->with('class_subjects', function ($q) {
                        return $q->with('subject');
                    });
                })
                ->first();

            $result = [];
            foreach ($stream->school_class->class_subjects as $subject) {
                $strands = Strand::where('subject_id', $subject->subject->id)
                    ->with('sub_strands', function ($q) {
                        return $q->with('learning_activities');
                    })
                    ->get();
                $total_learning_activities = 0;
                $learning_activities_ids = [];
                if ($strands->count()) {
                    foreach ($strands as $strand) {
                        foreach ($strand->sub_strands->pluck('learning_activities') as $learning_activity) {
                            foreach ($learning_activity as $activity) {
                                $learning_activities_ids[] = $activity->id;
                            }
                            $learning_activities_ids = array_unique($learning_activities_ids);
                            $total_learning_activities += count($learning_activity);
                        }
                    }
                }

                $attempted_activities = StudentAssessment::where([
                    'learner_id' => $learner_id,
                    'subject_id' => $subject->subject->id,
                    'stream_id' => $stream_id,
                    'term_id' => $term_id,
                ])
                    ->with('level')
                    ->whereIn('learning_activity_id', $learning_activities_ids)
                    ->get();

                $attempted_points = $attempted_activities->pluck('level')->pluck('points')->sum() / ($total_learning_activities ? $total_learning_activities : 1);

                $result[] = [
                    'id' => $subject->subject->id,
                    'name' => $subject->subject->title,
                    'total_learning_activity' => $total_learning_activities,
                    'attempted_activities' => $attempted_activities->pluck('level')->pluck('points')->count(),
                    'attempted_points' => round($attempted_points, 2),
                ];
            }

            $learner = User::find($learner_id);
            $term = Term::find($term_id);

            return view('formative-assessments.report', compact('learner', 'result', 'stream', 'term'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function downloadPdf($learner_id) {
        try {

        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
