<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use App\Models\Term;
use App\Models\TermSubject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Auth;

class TermsSubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $term = null;
            $selected_ids = [];
            if ($request->has('edit') && $request->get('pass_key')) {
                $term = Term::where(['id' => $request->get('pass_key')])
                    ->with('subjects')
                    ->first();
                $selected_ids = $term->subjects->pluck('subject_id');
            }
            $terms = Term::latest()->get();
            $subjects = Subjects::all();

            return view('term-subjects.index', compact('term','terms', 'subjects', 'selected_ids'));
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
        $terms = Term::with('subjects')->has('subjects')->get();
        $hasManagePermission = Auth::user()->can('manage_terms');

        return Datatables::of($terms)
            ->addColumn('subjects', function ($data) {
                $output = '';
                $subjects = $data->subjects;
                foreach ($subjects as $subject) {
                    $output .= '<span class="badge badge-dark m-1">' . $subject->subject->title . '</span>';
                }

                return $output;
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('term-subjects.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('term-subjects.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
                }

                return $output;
            })
            ->rawColumns(['subjects', 'action'])
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
            $subject_ids = $input['subject_ids'];
            foreach ($subject_ids as $id) {
                $term = [
                    'term_id' => $input['term_id'],
                    'subject_id' => $id
                ];

                TermSubject::create($term);
            }

            return redirect()->back()->with('success', 'Subjects added to term');
        }  catch (\Exception $e) {
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
            TermSubject::where('term_id', $input['term_id'])->delete();
            $subject_ids = $input['subject_ids'];
            foreach ($subject_ids as $id) {
                $term = [
                    'term_id' => $input['term_id'],
                    'subject_id' => $id
                ];

                TermSubject::create($term);
            }

            return redirect()->back()->with('success', 'Subjects added to term');
        }  catch (\Exception $e) {
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
            TermSubject::where('term_id', $id)->delete();

            return redirect()->route('term-subjects.index')->with('success', 'Subjects deleted from term');
        }  catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
