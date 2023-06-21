<?php

namespace App\Http\Controllers;

use App\Models\LearningActivity;
use App\Models\Strand;
use App\Models\Substrand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Auth;

class SubStrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $sub_strand = null;
            if ($request->has('edit') && $request->get('pass_key')) {
                $sub_strand = Substrand::where(['id' => $request->get('pass_key')])->first();
            }
            $strands = Strand::all();

            return view('sub_strands.index', compact('sub_strand', 'strands'));
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
        $data = Substrand::latest()->get();
        $hasManagePermission = Auth::user()->can('manage_strands');

        return Datatables::of($data)
            ->addColumn('subject', function ($data) {
                if (!empty($data->strand)) {
                    return $data->strand->subject->title;
                }

                return "";
            })
            ->addColumn('strand', function ($data) {
                if (!empty($data->strand)) {
                    return $data->strand->title;
                }

                return "";
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('sub-strands.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('sub-strands.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->except('_token');;

            Substrand::create($input);
            return redirect()->back()->with('success', 'Sub Strand Added Successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->except('_token');;

            Substrand::where('id', $id)->update($input);
            return redirect()->back()->with('success', 'Sub Strand Updated Successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $sub_strand = Substrand::where('id', $id)->first();
            $sub_strand->learning_activities()->delete();
            $sub_strand->delete();

            return redirect()->back()->with('success', 'Sub Strand Deleted Successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function getLearningActivities($id) {
        try {
            $learning_activities = LearningActivity::where('sub_strand_id', $id)->get();
            $html = '<option>Select Learning Activity</option>';
            foreach ($learning_activities as $learning_activity) {
                $html .= '<option value="' . $learning_activity->id . '">' . $learning_activity->title . '</option>';
            }

            return $html;
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
