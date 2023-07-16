<?php

namespace App\Http\Controllers;

use App\Models\SummativePerformnceLevel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SummativePerformanceLevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $level = null;
            if ($request->has('edit') && $request->get('pass_key')) {
                $level = SummativePerformnceLevel::where(['id' => $request->get('pass_key')])->first();
            }
            $admins = getSchoolAdmins();
            $min = SummativePerformnceLevel::whereIn('created_by', $admins)->max('max_point');
            if (!$min) {
                $min = 0;
            } else {
                $min += 1;
            }

            return view('summative-performance-levels.index', compact('level', 'min'));
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
        $admins = getSchoolAdmins();
        $data = SummativePerformnceLevel::when(in_array(Auth::user()->role, ['admin', 'teacher']), function ($q) use ($admins) {
            return $q->whereIn('created_by', $admins);
        })->latest()->get();
        $hasManagePermission = Auth::user()->can('manage_summative_performance_levels');

        return Datatables::of($data)
            ->addColumn('points', function ($data) {
                return $data->min_point . ' - ' . $data->max_point;
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('summative-performance-levels.index', ['edit' => 1, 'pass_key' => $data->id]) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('summative-performance-levels.delete', ['id' => $data->id]) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
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
            $input['created_by'] = Auth::id();
            SummativePerformnceLevel::create($input);

            return redirect()->back()->with('success', 'Performance Level Created');
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
            SummativePerformnceLevel::where('id', $id)->update($input);

            return redirect()->route('summative-performance-levels.index')->with('success', 'Performance Level Updated');
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
            SummativePerformnceLevel::where('id', $id)->delete();

            return redirect()->back()->with('success', 'Performance Level Deleted');
        } catch (\Exception $e) {
            $bug = $e->getMessage();

            return redirect()->back()->with('error', $bug);
        }
    }
}
