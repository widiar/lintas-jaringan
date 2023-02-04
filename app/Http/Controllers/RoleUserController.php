<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class RoleUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::where('name', '<>', 'super admin')->get(['id', 'name']);
            return DataTables::of($roles)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                    if (!empty($request->get('name'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['name']), Str::lower($request->get('name'))) ? true : false;
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    $urlShow    = ' <a href="' . route('admin.roles.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    $urlEdit    = ' <a href="' . route('admin.roles.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';
                    $urlDestroy = ' <a href="' . route('admin.roles.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';

                    // $btn = (count(auth()->user()->userPermission) == 0 || auth()->user()->currentPagePermission(Route::currentRouteName())->read_act ? $urlShow : '') .
                    //     (count(auth()->user()->userPermission) == 0 || auth()->user()->currentPagePermission(Route::currentRouteName())->update_act ? $urlEdit : '') .
                    //     (count(auth()->user()->userPermission) == 0 || auth()->user()->currentPagePermission(Route::currentRouteName())->delete_act ? $urlDestroy : '');
                    // return $btn;
                    return $urlShow . $urlEdit . $urlDestroy;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.roles.index');
    }

    public function show($id)
    {
        $role = Role::find(Hashids::decode($id))->first();
        if (is_null($role)) abort(404);
        $permissions = $role->getPermissionNames()->toArray();
        return view('admin.roles.show', compact('role', 'permissions'));
    }

    public function create()
    {
        $permissions = [];
        return view('admin.roles.credit', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);
        $role = Role::create(['name' => $request->name]);
        if (isset($request->permission)) {
            $perm = Permission::whereIn('name', $request->permission)->get();
            $role->syncPermissions($perm);
        }

        return to_route('admin.roles')->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit($id)
    {
        $role = Role::find(Hashids::decode($id))->first();
        if (is_null($role)) abort(404);
        $permissions = $role->getPermissionNames()->toArray();
        return view('admin.roles.credit', compact('role', 'permissions', 'id'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::find(Hashids::decode($id))->first();
        if (is_null($role)) abort(404);
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        if (isset($request->permission)) {
            $perm = Permission::whereIn('name', $request->permission)->get();
            $role->syncPermissions($perm);
        } else {
            foreach ($role->permissions as $perm) {
                $role->revokePermissionTo($perm);
            }
        }

        return to_route('admin.roles')->with('success', 'Data Berhasil Diupdate!');
    }

    public function delete($id)
    {
        $role = Role::find(Hashids::decode($id))->first();
        if (is_null($role)) abort(404);
        $role->delete();
        return response()->json('Sukses');
    }
}
