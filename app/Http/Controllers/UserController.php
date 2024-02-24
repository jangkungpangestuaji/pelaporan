<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Peserta;
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
        $instansi_id = Auth::user()->instansi_id;
        $instansi = Instansi::find($instansi_id);
        $peserta = DB::table('peserta')
            ->select('id')
            ->where('instansi_id', '=', $instansi_id)
            ->get();
        // dd(count($peserta));
        $jml_peserta = count($peserta);
        $array = [
            'nama' => $nama,
            'jml_peserta' => $jml_peserta,
            'instansi' => $instansi->nama_instansi,
        ];
        return view('pages.profile.user', $array);
    }
}
