<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();
        $cre = [
            'username' => $request->username,
            'password' => $request->password
        ];
        if (Auth::attempt($cre)) {
            if (isset($request->next))
                return redirect($request->next);
            if ($user->hasRole('Pelanggan')) {
                return to_route('home');
            } else {
                return to_route('admin.dashboard');
            }
        } else {
            return redirect()->route('login')->with('status', 'Username atau Password anda salah')->withInput();
        }
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'nohp' => 'required',
        ]);

        $role = Role::where('name', 'Pelanggan')->first();
        // dd($role);

        $password = Hash::make($request->password);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $password
        ]);
        $user->assignRole($role->name);
        Pelanggan::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
            'user_id' => $user->id
        ]);

        return to_route('login')->with('success', 'Berhasil Register! Silahkan Login.');
    }

    public function logout()
    {
        Auth::logout();
        return to_route('home');
    }
}
