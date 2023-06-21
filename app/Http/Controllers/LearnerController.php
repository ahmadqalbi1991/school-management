<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\Models\School;
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
                    'link' => route('login')
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
}
