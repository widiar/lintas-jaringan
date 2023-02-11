<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function index()
    {
    }
    public function create()
    {
        return view('admin.permission.credit');
    }

    public function store(Request $request)
    {
        $perm = str_replace(" ", "_", $request->name);
        $perm = strtolower($perm);
        if (isset($request->options)) {
            Permission::create(['name' => $perm]);
        } else {
            $array = [
                ['name' => 'view_' . $perm, 'guard_name' => 'web'],
                ['name' => 'detail_' . $perm, 'guard_name' => 'web'],
                ['name' => 'create_' . $perm, 'guard_name' => 'web'],
                ['name' => 'edit_' . $perm, 'guard_name' => 'web'],
                ['name' => 'delete_' . $perm, 'guard_name' => 'web'],
                ['name' => 'print_' . $perm, 'guard_name' => 'web'],
            ];
            Permission::insert($array);
        }
        return to_route('admin.roles')->with('success', 'Permission Berhasil Ditambahkan!');
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
