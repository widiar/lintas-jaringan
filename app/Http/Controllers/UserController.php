<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\Pelanggan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('super admin'))
                $users = User::where('id', '<>', Auth::user()->id)->get();
            else {
                $users = User::role('Pelanggan')->get();
            }
            return DataTables::of($users)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['username']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            if (Str::contains(Str::lower($row['email']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            if (Str::contains(Str::lower($row['role']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('role', function ($row) {
                    return $row->getRoleNames()->toArray()[0];
                })
                ->addColumn('action', function ($row) {
                    $urlShow    = ' <a href="' . route('admin.user.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    $urlEdit    = ' <a href="' . route('admin.user.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';
                    $urlDestroy = ' <a href="' . route('admin.user.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';
                    $btn = (auth()->user()->can('detail_user') ? $urlShow : '') . (auth()->user()->can('edit_user') ? $urlEdit : '') . (auth()->user()->can('delete_user') ? $urlDestroy : '');
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.user.index');
    }

    public function create()
    {
        $roles = Role::get(['id', 'name']);
        return view('admin.user.credit', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required'
        ]);

        // dd($request->all());

        $password = Hash::make($request->password);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $password,
            'email_verified_at' => Carbon::now()
        ]);
        $user->assignRole($request->role);

        if ($request->role == 'Pelanggan') {
            Pelanggan::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'nohp' => $request->nohp,
                'user_id' => $user->id
            ]);
        }

        return to_route('admin.user')->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function show($id)
    {
        $user = User::with('pelanggan')->where('id', Hashids::decode($id))->firstOrFail();
        return view('admin.user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('pelanggan')->where('id', Hashids::decode($id))->firstOrFail();
        $roles = Role::get(['id', 'name']);
        $perm = $user->getRoleNames()->toArray()[0];
        return view('admin.user.credit', compact('user', 'roles', 'id', 'perm'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('pelanggan')->where('id', Hashids::decode($id))->firstOrFail();
        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required'
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();
        $user->syncRoles($request->role);

        if ($request->role == 'Pelanggan') {
            $user->pelanggan->nama = $request->nama;
            $user->pelanggan->alamat = $request->alamat;
            $user->pelanggan->nohp = $request->nohp;
            $user->pelanggan->save();
        }

        return to_route('admin.user')->with('success', 'Data Berhasil Dirubah!');
    }

    public function destroy($id)
    {
        $user = User::with('pelanggan')->where('id', Hashids::decode($id))->firstOrFail();
        if ($user->pelanggan) $user->pelanggan->delete();
        $user->delete();
        return response()->json('Sukses');
    }
}
