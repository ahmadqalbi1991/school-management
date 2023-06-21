<?php

namespace App\Http\Controllers;

use App\Models\LearningActivity;
use App\Models\Substrand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Auth;

class LearningActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $learning_activity = null;
            if ($request->has('edit') && $request->get('pass_key')) {
                $learning_activity = LearningActivity::where(['id' => $request->get('pass_key')])->first();
            }
            $sub_strands = Substrand::all();

            return view('learning-activities.index', compact('sub_strands', 'learning_activity'));
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
        $data = LearningActivity::latest()->get();
        $hasManagePermission = Auth::user()->can('manage_strands');

        return Datatables::of($data)
            ->addColumn('sub_strand', function ($data) {
                if (!empty($data->sub_strand)) {
                    return $data->sub_strand->title;
                }

                return "";
            })
            ->addColumn('strand', function ($data) {
                if (!empty($data->sub_strand)) {
                    if (!empty($data->sub_strand->strand)) {
                        return $data->sub_strand->strand->title;
                    }
                }

                return "";
            })
            ->addColumn('subject', function ($data) {
                if (!empty($data->sub_strand)) {
                    if (!empty($data->sub_strand->strand)) {
                        if (!empty($data->sub_strand->strand->subject)) {
                            return $data->sub_strand->strand->subject->title;
                        }
                    }
                }

                return "";
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('learning-activities.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('learning-activities.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
                }

                return $output;
            })
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
            $input = $request->except('_token');;

            LearningActivity::create($input);
            return redirect()->back()->with('success', 'Learning Activity Added Successfully');
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
            $input = $request->except('_token');;

            LearningActivity::where('id', $id)->update($input);
            return redirect()->back()->with('success', 'Learning Activity Updated Successfully');
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
            LearningActivity::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Learning Activity Deleted Successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
