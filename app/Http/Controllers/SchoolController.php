<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolAdmins;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Auth;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('settings.school.index');
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
        $data = School::latest()->get();
        $hasManagePermission = Auth::user()->can('manage_settings');

        return Datatables::of($data)
            ->addColumn('logo', function ($data) {
                $imgUrl = asset('img/No-img.png');
                if ($data->logo) {
                    $imgUrl = asset($data->logo);
                }

                return '<img src="' . $imgUrl . '" width="50" />';
            })
            ->addColumn('school_website', function ($data) {
                return '<a href="' . $data->school_website . '" target="_blank">Visit Website</a>';
            })
            ->addColumn('active', function ($data) use ($hasManagePermission) {
                $output = '';
                $btn = 'danger';
                $status = 'Off';

                if ($data->active) {
                    $status = 'On';
                    $btn = 'success';
                }
                if ($hasManagePermission) {
                    $output = '<a href="" class="btn btn-' . $btn . '">' . $status .'</a>';
                }

                return $output;
            })
            ->addColumn('action', function ($data) use ($hasManagePermission) {
                $output = '';
                if ($hasManagePermission) {
                    $output = '<div class="">
                                    <a href="' . route('settings.schools.edit', $data->id) . '"><i class="ik ik-edit f-16 text-blue"></i></a>
                                    <a href="' . route('settings.schools.delete', $data->id) . '"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
                }

                return $output;
            })
            ->rawColumns(['action', 'active', 'school_website', 'logo'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $admins = User::where(['role' => 'admin', 'status' => 'active'])
                ->with('school_admins')
                ->doesntHave('school_admins')
                ->get();

            return view('settings.school.create', compact('admins'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
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
            $input['active'] = $input['status'] === 'active' ? 1 : 0;
            $input['slug'] = Str::slug($input['school_name']);
            $ids = $input['admin_ids'];
            unset($input['admin_ids']);
            if ($request->has('logo')) {
                $imageName = $input['slug'] . '_' . time().'.'.$request->logo->extension();
                $request->logo->move(public_path('images/schools/' . $input['slug'] . '/logo'), $imageName);
                $input['logo'] = 'images/schools/' . $input['slug'] . '/logo/' . $imageName;
            }
            $school = School::create($input);
            if ($school) {
                $idObj = [];
                foreach ($ids as $id) {
                    $idObj[] = [
                        'school_id' => $school->id,
                        'admin_id' => $id
                    ];
                }
                SchoolAdmins::insert($idObj);
            }

            return redirect()->route('settings.schools.index')->with('success', 'School created successfully');
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
        try {
            $school = School::where(['id' => $id])->with('admins')->first();
            $selected_admins = $school->admins->pluck('admin_id')->toArray();
            $admins = User::where(['role' => 'admin', 'status' => 'active'])
                ->with('school_admins', function ($q) use ($selected_admins) {
                    return $q->whereIn('admin_id', $selected_admins);
                })
                ->get();

            return view('settings.school.edit', compact('admins', 'school', 'selected_admins'));
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
            $input['active'] = !empty($input['status']) ? 1 : 0;
            $input['slug'] = Str::slug($input['school_name']);
            $ids = $input['admin_ids'];
            unset($input['admin_ids'], $input['status']);
            if ($request->has('logo')) {
                $imageName = $input['slug'] . '_' . time().'.'.$request->logo->extension();
                $request->logo->move(public_path('images/schools/' . $input['slug'] . '/logo'), $imageName);
                $input['logo'] = 'images/schools/' . $input['slug'] . '/logo/' . $imageName;
            }

            School::where('id', $id)->update($input);
            $school = School::where('id', $id)->first();
            if ($school) {
                $school->admins()->delete();
                $idObj = [];
                foreach ($ids as $id) {
                    $idObj[] = [
                        'school_id' => $school->id,
                        'admin_id' => $id
                    ];
                }
                SchoolAdmins::insert($idObj);
            }

            return redirect()->route('settings.schools.index')->with('success', 'School updated successfully');
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
            $school = School::where('id', $id)->first();
            $school->admins()->delete();
            $school->delete();

            return redirect()->route('settings.schools.index')->with('success', 'School deleted successfully');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function getClasses(Request $request, $id) {
        try {
            if ($request->has('teacher') && $request->get('teacher')) {
                $teacher = User::where('id', $id)->first();
                $id = $teacher->school_id;
            }
            $classes = SchoolClass::where('school_id', $id)->get();
            $html = '<option value="">Select Class</option>';

            foreach ($classes as $class) {
                $html .= '<option value="' . $class->id . '">' . $class->class . '</option>';
            }

            return $html;
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
