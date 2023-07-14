<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $class = null;
            $schools = School::where('active', 1)->get();
            if ($request->has('edit') && $request->get('pass_key')) {
                $class = SchoolClass::where(['id' => $request->get('pass_key')])->first();
            }

            return view('classes.index', compact('class', 'schools'));
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
        $data = SchoolClass::latest()->get();
        $hasManagePermission = Auth::user()->can('manage_classes');

        return Datatables::of($data)
            ->addColumn('school', function ($data) {
                $school_name = '';
                if (!empty($data->school)) {
                    $school_name = $data->school->school_name;
                }
                return $school_name;
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('classes.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('classes.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
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
            $input = $request->except('_token');
            $input['status'] = 1;
            $input['slug'] = Str::slug($input['class']);

            SchoolClass::create($input);
            return redirect()->back()->with('success', 'Class added successfully');
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
            $input = $request->except('_token');
            $input['slug'] = Str::slug($input['class']);

            SchoolClass::where('id', $id)->update($input);
            return redirect()->back()->with('success', 'Class updated successfully');
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
            SchoolClass::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Class deleted successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function getStreams($id) {
        try {
            $streams = Stream::where('class_id', $id)->get();
            $subjects = Subjects::where('class_id', $id)
                ->get();

            $stream_html = '<option value="">Select Stream</option>';
            $subjects_html = '<option value="">Select Subjects</option>';
            foreach ($streams as $stream) {
                $stream_html .= '<option value="' . $stream->id . '">' . $stream->title . '</option>';
            }

            foreach ($subjects as $subject) {
                $subjects_html .= '<option value="' . $subject->id . '">' . $subject->title . '</option>';
            }

            return ['streams' => $stream_html, 'subjects' => $subjects_html];
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
