<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\Models\AssignedSubject;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\TeacherManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $teacher = null;
            if ($request->has('edit') && $request->get('pass_key')) {
                $teacher = User::where(['id' => $request->get('pass_key'), 'role' => 'teacher'])->first();
            }
            $schools = School::where('active', 1)->get();

            return view('teachers.index', compact('teacher', 'schools'));
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
        $data = User::where(['role' => 'teacher'])
            ->with('school')
            ->when(Auth::user()->role === 'admin', function ($q) {
                return $q->where('school_id', Auth::user()->school_id);
            })
            ->get();
        $hasManagePermission = Auth::user()->can('manage_teachers');
        $hasManageTeachers = Auth::user()->can('manage_assigned_teachers');

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
            ->addColumn('action', function ($data) use ($hasManagePermission, $hasManageTeachers) {
                $output = '';
                if ($hasManagePermission) {
                    $output .= '<div class="">';
                    $output .= '<a href="' . route('teachers.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>';
                    $output .= '<a href="' . route('teachers.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>';
                    if ($hasManageTeachers) {
                        $output .= '<a href="' . route('teachers.show', ['id' => $data->id]) . '"><i class="ik ik-eye f-16 text-green"></i></a>';
                    }
                    $output .= '</div>';
                }

                return $output;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
            $input = $request->except('_token');;
            $input['password'] = $password;
            $input['role'] = 'teacher';

            $user = User::create($input);

            if ($user) {
                $details = [
                    'title' => 'Registration Successful',
                    'subject' => 'Registration Successful',
                    'body' => 'Congratulations! Your account has been created on ' . env('APP_NAME') . '. Please use the following details to login. <br/>Email: <strong>' . $input['email'] . '</strong> <br/>Password: <strong>' . $password . '</strong>',
                    'email' => $input['email'],
                    'show_btns' => 1,
                    'link' => route('login')
                ];

                $user->syncRoles('teacher');

                dispatch(new EmailJob($details));
            }
            return redirect()->back()->with('success', 'Teacher added successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $teacher = User::where('id', $id)->first();
            if (!$teacher) {
                return redirect()->back()->with('error', 'Teacher not found');
            }

            $teacher->name = $request->get('name');
            $teacher->phone_number = $request->get('phone_number');
            $teacher->tsc_number = $request->get('tsc_number');
            $teacher->status = $request->get('status');
            $teacher->school_id = $request->get('school_id');
            $teacher->save();

            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $teacher = User::where('id', $id)->first();
            if (!$teacher) {
                return redirect()->back()->with('error', 'Teacher not found');
            }
            $teacher->delete();

            return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function manageTeachers()
    {
        try {
            $teachers = User::where([
                'status' => 'active',
                'role' => 'teacher'
            ])
                ->when(Auth::user()->role === 'admin', function ($q) {
                    return $q->where('school_id', Auth::user()->school_id);
                })->get();

            return view('teachers.manage-teachers', compact('teachers'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $teachers = User::where([
                'status' => 'active',
                'role' => 'teacher'
            ])
                ->when(Auth::user()->role === 'admin', function ($q) {
                    return $q->where('school_id', Auth::user()->school_id);
                })->get();
            $teacher = User::findOrFail($id);
            $records = TeacherManagement::where('teacher_id', $id)
                ->with(['class', 'stream'])
                ->get();
            $subjects = AssignedSubject::with('subject')->where('teacher_id', $id)->get();
            $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();

            return view('teachers.detail', compact('teacher', 'records', 'subjects', 'teachers', 'classes'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveManageTeachers(Request $request)
    {
        try {
            $input = $request->except('_token');

            $exist = TeacherManagement::where($input)->first();
            if ($exist) {
                return back()->with('error', 'Teacher already assigned to class and stream');
            }
            TeacherManagement::create($input);

            return back()->with('success', 'Teacher added to class');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveManageTeachersSubjects(Request $request)
    {
        try {
            $input = $request->except('_token');
            $subject_ids = $input['subject_ids'];
            unset($input['subject_ids']);
            $exist = TeacherManagement::where($input)->first();
            if (!$exist) {
                return back()->with('error', 'Teacher is not assigned to class');
            }

            foreach ($subject_ids as $id) {
                $assigned = AssignedSubject::where(['teacher_id' => $input['teacher_id'], 'subject_id' => $id])->first();
                if (!$assigned) {
                    AssignedSubject::create(['teacher_id' => $input['teacher_id'], 'subject_id' => $id]);
                }
            }

            return back()->with('success', 'Subjects added');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeClass($id)
    {
        try {
            TeacherManagement::where('id', $id)->delete();

            return back()->with('success', 'Teacher removed');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeSubject($id)
    {
        try {
            AssignedSubject::where('id', $id)->delete();

            return back()->with('success', 'Subject removed');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
