<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function index()
    {

        return view('login');
    }
    // public function viewlogin(){

    //     return view('auth.login');
    // }

    public function auth(Request $request)
    {

        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Ubah kolom 'username' menjadi 'email'
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($validated) or Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $notification = array(
                'status' => 'toast_success',
                'title' => 'Login Berhasil',
                'message' => 'Login Berhasil',

            );

            $id = Auth::user()->id;

            $update = User::find($id);
            $update->updated_at = now();
            $update->save();

            return redirect()->intended('/home')->with($notification);
        }

        $notification = array(
            'status' => 'error',
            'title' => 'Login Gagal',
            'message' => 'Username / Password Salah',
        );
        return back()->with($notification);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $notification = array(
            'status' => 'toast_success',
            'title' => 'Logout berhasil',
            'message' => 'Terima kasih telah menggunakan aplikasi kami'
        );

        return redirect('/')->with($notification);
    }
}
