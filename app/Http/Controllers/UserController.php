<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    public function index()
    {
        $nama = Auth::user()->name;
        $user = Auth::user();
        $instansi = Instansi::find($user->instansi_id);
        $peserta = DB::table('peserta')
            ->select('id')
            ->where('instansi_id', '=', $user->instansi_id)
            ->get();

        $jml_peserta = count($peserta);
        $array = [
            'nama' => $nama,
            'jml_peserta' => $jml_peserta,
            'instansi' => $instansi->nama_instansi,
            'user' => $user,
        ];
        return view('pages.profile.user', $array);
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $data = User::find($id);

        return response()->json(['data' => $data]);
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $data = User::find($id);

        // Mengatur update array dengan data yang ingin diperbarui
        $update = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ];
        // Menambahkan kondisi untuk memperbarui password hanya jika tidak kosong atau null
        if ($request->filled('password')) {
            $update['password'] = bcrypt($request->password);
        }
        
        // Memperbarui data pengguna
        $data->update($update);

        return response()->json(['data' => $data]);
    }
}
