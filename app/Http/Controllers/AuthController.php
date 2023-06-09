<?php

namespace App\Http\Controllers;

use App\Mail\RegisterUser;
use App\Mail\ResetPasswordMail;
use App\Models\Invoice;
use App\Models\PasswordReset;
use App\Models\Pelanggan;
use App\Models\Teknisi;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
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

        $cre = [
            'username' => $request->username,
            'password' => $request->password
        ];
        if (Auth::attempt($cre)) {
            $user = User::where('username', $request->username)->first();
            $msg2 = '';
            if ($user->hasRole('Pelanggan')) $msg = '';
            else if ($user->hasRole('Teknisi')) {
                $teknisi = Teknisi::where('user_id', $user->id)->first();
                $jml = Invoice::where('status', 'PROSES')->where('teknisi_id', $teknisi->id)->count();
                $res = Invoice::where('status', 'RESCHEDULE')->where('teknisi_id', $teknisi->id)->count();
                $msg = 'Terdapat ' . $jml . ' invocie yang harus dipasang dan ' . $res . ' yang di reschedule.';
            } else {
                $pending = Invoice::where('status', 'PENDING')->count();
                $paid = Invoice::where('status', 'PAID')->count();
                $msg = 'Terdapat Pembelian terbaru dengan total <br>' . $pending . ' belum bayar dan ' . $paid . ' sudah dibayar';
    
                $res = Invoice::where('status', 'RESCHEDULE')->count();
                if ($res > 0)
                    $msg2 = 'Terdapat ' . $res . ' invoice yang harus di <b>Reschedule</b>';
            }
            if (isset($request->next))
                return redirect($request->next)->with('message', $msg);
            if ($user->hasRole('Pelanggan')) {
                if (is_null($user->email_verified_at)) return to_route('login')->with('status', 'Akun anda belum terverifikasi.');
                return to_route('home');
            } else if ($user->hasRole('Teknisi')) {
                return to_route('invoice')->with('message', $msg);
            } else {
                return to_route('admin.dashboard')->with(['message' => $msg, 'message2' => $msg2]);
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

    public function forgot()
    {
        return view('auth.forgot');
    }

    public function postForgot(Request $request)
    {
        $request->validate([
            'username' => 'required',
        ]);

        $user = User::where('email', $request->username)->orWhere('username', $request->username)->first();
        if($user){
            if($user->hasRole('Pelanggan')){
                if(!is_null($user->email_verified_at)){
                    PasswordReset::where('email', $user->email)->delete();
                    $token = base64_encode(random_bytes(17));
                    PasswordReset::insert([
                        'email' => $user->email,
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ]);
                    Mail::to($user->email)->send(new ResetPasswordMail($user->email, $token));
                    return redirect()->route('forgot-password')->with('success', 'Silahkan Cek Email Anda.');
                }else{
                    return to_route('forgot-password')->with('status', 'Email atau username belum aktif.');
                }

            }
        }
        return to_route('forgot-password')->with('status', 'Email atau username tidak terdaftar.');
    }

    public function resetPassword(Request $request)
    {
        $email = urldecode($request->get('email'));
        $token = urldecode($request->get('token'));
        // dd($token, $email);
        if(!(empty($email)) && !empty($token)){
            $passwordReset = PasswordReset::where('email', $email)->firstOrFail();
            $expire = new DateTime($passwordReset->created_at);
            $expire->modify('+12 hour');
            if (new DateTime() < $expire) {
                if (strcmp($token, $passwordReset->token) == 0) {
                    return view('auth.reset');
                }
            }
            return to_route('login')->with('status', 'Link Expired');
        }
        return to_route('login');
    }

    public function resetPasswordPost(Request $request)
    {
        $email = urldecode($request->get('email'));
        $token = urldecode($request->get('token'));

        if(!(empty($email)) && !empty($token)){
            $user = User::where('email', $email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            PasswordReset::where('email', $email)->delete();
            return to_route('login')->with('success', 'Password berhasil diubah silahkan login');
        }
        return to_route('login');
    }
}
