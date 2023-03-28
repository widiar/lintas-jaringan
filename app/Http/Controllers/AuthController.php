<?php

namespace App\Http\Controllers;

use App\Mail\RegisterUser;
use App\Models\Pelanggan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Vinkla\Hashids\Facades\Hashids;

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
                if (is_null($user->email_verified_at)) return to_route('login')->with('status', 'Akun anda belum terverifikasi.');
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
        $url = route('activate.user', ['id' => Hashids::encode($user->id)]);
        Mail::to($request->email)->send(new RegisterUser($url));

        return to_route('login')->with('success', 'Cek email anda untuk verifikasi akun.');
    }

    public function logout()
    {
        Auth::logout();
        return to_route('home');
    }

    public function activate(Request $request)
    {
        $id = Hashids::decode($request->id);
        $user = User::where('id', $id)->firstOrFail();
        if (is_null($user)) abort(404);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return to_route('login')->with('success', 'Akun anda telah aktif. Silahkan Login');
    }

    public function editProfile()
    {
        if (!Auth::user()->hasRole('Pelanggan')) abort(403);
        $user = User::with('pelanggan')->where('id', Auth::user()->id)->first();
        if (is_null($user)) abort(404);
        return view('admin.profile.edit', compact('user'));
    }

    public function editProfilePost(Request $request)
    {
        $user = User::with('pelanggan')->where('id', Auth::user()->id)->first();
        if (is_null($user)) abort(404);
        $user->pelanggan->nama = $request->nama;
        $user->pelanggan->alamat = $request->alamat;
        $user->pelanggan->nohp = $request->nohp;
        $user->pelanggan->save();

        return to_route('edit.profile')->with('success', 'Data Berhasil Diupdate!');
    }

    public function changePassword(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        if (!Hash::check($request->passwordLama, $user->password)) {
            return response()->json(['status' => 'password_salah']);
        }
        $user->password = Hash::make($request->passwordBaru);
        $user->save();
        return response()->json(['status' => 'success']);
    }
}
