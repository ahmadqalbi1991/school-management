<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolAdmins;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $admins = User::where(['role' => 'admin', 'status' => 'active'])->get();

            return view('settings.school.create', compact('admins'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
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

            return redirect()->route('settings.index')->with('success', 'School created successfully');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
