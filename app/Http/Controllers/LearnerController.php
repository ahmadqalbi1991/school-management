<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\Models\ClassSubject;
use App\Models\LearnerSubject;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth, DataTables;
use Illuminate\Support\Facades\Validator;

class LearnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $learner = null;
            if ($request->has('edit') && $request->get('pass_key')) {
                $learner = User::where(['id' => $request->get('pass_key'), 'role' => 'learner'])->first();
            }
            $schools = School::where('active', 1)->get();
            $streams = Stream::with(['school', 'school_class'])->get();

            return view('learners.index', compact('learner', 'schools', 'streams'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getList()
    {
        $data = User::where(['role' => 'learner'])
            ->with('school')
            ->when(Auth::user()->role === 'admin', function ($q) {
                return $q->where('school_id', Auth::user()->school_id);
            })
            ->get();
        $hasManagePermission = Auth::user()->can('manage_learners');

        return Datatables::of($data)
            ->addColumn('name', function ($data) {
                return ucfirst(str_replace('_', ' ', $data->name));
            })
            ->addColumn('school', function ($data) {
                return $data->school->school_name;
            })
            ->addColumn('status', function ($data) {
                $status = '';
                if ($data->status === 'active') {
                    $status = 'success';
                }

                if ($data->status === 'disable') {
                    $status = 'danger';
                }

                if ($data->status === 'blocked') {
                    $status = 'warning';
                }
                return '<span class="badge badge-' . $status . ' m-1">' . ucfirst($data->status) . '</span>';
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('learners.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('learners-subjects.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-book-open f-16 text-green"></i></a>
                                    <a href="' . route('learners.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
                }

                return $output;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'unique:users'
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            }

            $password = '123456789';
            $input = $request->except('_token');
            $input['password'] = bcrypt($password);
            $input['role'] = 'learner';
            $user = User::create($input);

            if ($user) {
                $details = [
                    'title' => 'Registration Successful',
                    'body' => 'Congratulations! Your account has been created on ' . env('APP_NAME') . '. Please use the following details to login. <br/>Email: <strong>' . $input['email'] . '</strong> <br/>Password: <strong>' . $password . '</strong>',
                    'email' => $input['email'],
                    'show_btns' => 1,
                    'link' => route('login'),
                    'subject' => 'Registration Successful'
                ];

                dispatch(new EmailJob($details));
            }

            return redirect()->route('learners.index')->with('success', 'Learner added successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->except('_token');
            User::where('id', $id)->update($input);

            return redirect()->route('learners.index')->with('success', 'Learner updated successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::where('id', $id)->delete();

            return redirect()->route('learners.index')->with('success', 'Learner deleted successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function addLearnerSubjects(Request $request)
    {
        try {
            $classes = SchoolClass::all();
            $learner = $class_id = $learner_data = $streams = $subjects = $subjects_ids = null;
            if ($request->has('pass_key') && $request->has('edit')) {
                $learner = User::where('id', $request->get('pass_key'))
                    ->with('stream', function ($q) {
                        return $q->with('school_class');
                    })->first();

                $class_id = $learner->stream->school_class->id;
                $streams = Stream::where('class_id', $class_id)->get();
                $subjects = ClassSubject::with('subject')
                    ->where('class_id', $class_id)->get();

                $learner_data = LearnerSubject::where('learner_id', $request->get('pass_key'))
                    ->with(['subject'])->get();

                $subjects_ids = $learner_data->pluck('subject_id')->toArray();
            }

            return view('learners.add-subjects', compact('classes', 'learner', 'learner_data', 'class_id', 'streams', 'subjects', 'subjects_ids'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveLearnerSubjects(Request $request)
    {
        try {
            $input = $request->except('_token');
            $learner_ids = $input['learner_ids'];
            $subject_ids = $input['subject_ids'];
            unset($input['subject_ids'], $input['learner_ids']);

            foreach ($learner_ids as $learner_id) {
                foreach ($subject_ids as $id) {
                    $input['subject_id'] = $id;
                    $input['learner_id'] = $learner_id;
                    LearnerSubject::create($input);
                }
            }

            return back()->with('success', 'Subjects assigned to learner');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLearnerSubjects(Request $request)
    {
        try {
            $input = $request->except('_token');
            $learner_ids = $input['learner_ids'];
            $subject_ids = $input['subject_ids'];
            unset($input['subject_ids'], $input['learner_ids']);

            foreach ($learner_ids as $learner_id) {
                foreach ($subject_ids as $id) {
                    $exists_subject = LearnerSubject::where([
                        'subject_id' => $id,
                        'learner_id' => $learner_id,
                        'class_id' => $input['class_id'],
                        'stream_id' => $input['stream_id']
                    ])->first();

                    if (!$exists_subject) {
                        $input['subject_id'] = $id;
                        $input['learner_id'] = $learner_id;
                        LearnerSubject::create($input);
                    }
                }
            }

            return back()->with('success', 'Subjects assigned to learner');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
