<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return redirect()->route('login')->with('status', 'Email atau Password anda salah')->withInput();
        }
    }
    public function logout()
    {
        Auth::logout();
        return to_route('home');
    }
}
