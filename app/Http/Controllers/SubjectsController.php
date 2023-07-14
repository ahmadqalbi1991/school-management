<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $subject = null;
            if ($request->has('edit') && $request->get('pass_key')) {
                $subject = Subjects::where(['id' => $request->get('pass_key')])->first();
            }
            $classes = SchoolClass::when(Auth::user()->role === 'admin', function ($q) {
                return $q->where('school_id', Auth::user()->school_id);
            })->get();

            return view('subjects.index', compact('subject', 'classes'));
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
        $data = Subjects::with('school_class')->latest()->get();
        $hasManagePermission = Auth::user()->can('manage_subjects');

        return Datatables::of($data)
            ->addColumn('class', function ($data) {
                $class = '';
                if (!empty($data->school_class)) {
                    $class = $data->school_class->class;
                }

                return $class;
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('subjects.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('subjects.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
                }

                return $output;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
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
            $input = $request->except('_token');
            $input['slug'] = Str::slug($input['title']);
            Subjects::create($input);

            return redirect()->back()->with('success', 'Subject added successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
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
            $input = $request->except('_token');
            $input['slug'] = Str::slug($input['title']);
            Subjects::where('id', $id)->update($input);

            return redirect()->back()->with('success', 'Subject updated successfully');
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
            $subject = Subjects::where('id', $id)->with('strands')->first();
            $strands = $subject->strands;
            foreach ($strands as $strand) {
                foreach ($strand->sub_strands as $sub_strand) {
                    $sub_strand->learning_activities()->delete();
                }
                $strand->sub_strands()->delete();
            }
            $subject->strands()->delete();
            $subject->terms()->delete();
            $subject->delete();

            return redirect()->back()->with('success', 'Subject deleted successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
