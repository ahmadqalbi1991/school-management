<?php

namespace App\Http\Controllers;

use App\Models\Strand;
use App\Models\Subjects;
use App\Models\Substrand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Auth;

class StrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $strand = null;
            if ($request->has('edit') && $request->get('pass_key')) {
                $strand = Strand::where(['id' => $request->get('pass_key')])->first();
            }
            $subjects = Subjects::all();

            return view('strands.index', compact('strand', 'subjects'));
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
        $data = Strand::latest()->get();
        $hasManagePermission = Auth::user()->can('manage_strands');

        return Datatables::of($data)
            ->addColumn('subject', function ($data) {
                if (!empty($data->subject)) {
                    return $data->subject->title;
                }

                return "";
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('strands.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('strands.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
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

            Strand::create($input);
            return redirect()->back()->with('success', 'Strand Added Successfully');
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

            Strand::where('id', $id)->update($input);
            return redirect()->route('strands.index')->with('success', 'Strand Updated Successfully');
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
            $strand = Strand::where('id', $id)->first();
            $strand->sub_strands()->delete();
            $strand->delete();

            return redirect()->route('strands.index')->with('success', 'Strand Deleted Successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function getSubStrands($id) {
        try {
            $substrands = Substrand::where('strand_id', $id)->get();
            $html = '<option>Select Sub Strand</option>';
            foreach ($substrands as $substrand) {
                $html .= '<option value="' . $substrand->id . '">' . $substrand->title . '</option>';
            }

            return $html;
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
