<?php

namespace App\Http\Controllers;

use App\Imports\DapenImport;
use App\Models\BuktiIuran;
use Illuminate\Http\Request;
use App\Models\Tahun;
use App\Models\DataIuran;
use App\Models\VerifikasiBerkas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class VerifikasiBerkasController extends Controller
{
    public function index()
    {
        $currentYear = date('Y');

        if (!Tahun::where('tahun', $currentYear)->exists()) {
            // Tambahkan data tahun baru
            Tahun::insert(['tahun' => $currentYear]);
        }

        $data = Tahun::orderBy('id', 'desc')->get();

        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('Aksi', function ($data) {
                    $button = "
                    <a href='/mitra/uploadBuktiPembayaran/{$data->id}' >
                    <button type='button' id='" . $data->id . "' class='btn btn-warning'  >
                        Buka
                    </button>
                    </a>";
                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.mitra.upload.dataPerTahun');
    }
    public function getDataByTahun($id)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $getIdTahun = DB::table('tahun')->select('id')->where('tahun', '=', $currentYear)->get();
        $getIdInstansi = Auth::user()->instansi_id;


        $cek = DB::table('bukti_iuran')
            ->where('tahun_id', '=', $getIdTahun[0]->id)
            ->where('instansi_id', '=', $getIdInstansi)
            ->exists();
        if (!$cek) {
            for ($n = 1; $n <= 12; $n++) {
                // dd($cek);

                $insert = [
                    'bulan_id' => $n,
                    'tahun_id' => $getIdTahun[0]->id,
                    'instansi_id' => $getIdInstansi,
                ];
                BuktiIuran::insert($insert);
            }
        }


        $data = DB::table('bukti_iuran')
            ->orderBy('bulan_id', 'asc')
            ->select('bukti_iuran.id', 'bulan.bulan', 'bukti_iuran.tahun_id', 'bukti_iuran.bulan_id', 'bukti_iuran.status')
            ->leftJoin('bulan', 'bukti_iuran.bulan_id', 'bulan.id')
            ->where('bukti_iuran.tahun_id', '=', $id)
            ->get();
        // dd($data);
        $array = [
            'data' => $data,
            'id' => $id
        ];

        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('Aksi', function ($data) use ($cek) {
                    return $cek;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }

        return view('pages.mitra.upload.dataPerBulan', $array, compact('data'));
    }
    public function upload(Request $request, $tahun, $bulan)
    {
        $id = $request->id;

        $request->validate([
            'file_name' => 'required|file', // Validasi bahwa input adalah file
            'deskripsi' => 'required', // Validasi bahwa deskripsi harus ada
        ]);

        if ($request->hasFile('file_name')) {
            $file = $request->file('file_name');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::uuid() . '.pdf';
            $data = [
                'file_name' => $fileName,
                'deskripsi' => $request->deskripsi,
                'status' => 1,
            ];
            $upload = BuktiIuran::find($id);
            $upload->update($data);
            $file->move(public_path('uploads'), $fileName);

            return response()->json(['data' => $data]);
        } else {
            return redirect()->back()->with('error', 'Mohon dicoba kembali');
        }
    }
    public function show(Request $request, $tahun, $bulan)
    {
        // $id = $request->id;
        $data = DB::table('bukti_iuran')
            ->select('id', 'file_name', 'deskripsi')
            ->where('tahun_id', '=', $tahun)
            ->where('bulan_id', '=', $bulan)
            ->get();
        // dd();
        return response()->json(['data' => $data]);
    }
    public function update(Request $request, $tahun, $bulan)
    {
        $id = $request->id;
        $request->validate([
            'file_name' => 'required|mimes:pdf|file', // Validasi bahwa input adalah file
            'deskripsi' => 'required', // Validasi bahwa deskripsi harus ada
        ]);
        if ($request->hasFile('file_name')) {
            $file = $request->file('file_name');
            $fileName = Str::uuid() . '.pdf';
            $update = [
                'file_name' => $fileName,
                'deskripsi' => $request->deskripsi,
            ];
            $data = BuktiIuran::find($id);
            $data->update($update);
            $data->save();
            if ($data) {
                $file->move(public_path('uploads'), $fileName);
            }
            return response()->json(['data' => $data]);
        }
    }

    public function index2()
    {
        $currentYear = date('Y');

        if (!Tahun::where('tahun', $currentYear)->exists()) {
            // Tambahkan data tahun baru
            Tahun::insert(['tahun' => $currentYear]);
        }
        $dataTahun = DB::table('tahun')->orderBy('id', 'asc')->get();
        // $dataInstansi = DB::table('instansi')->orderBy('id', 'asc')->where('id', '!=', '1')->get();
        $array = ([
            'type_menu' => 'verifikasi',
            'dataTahun' => $dataTahun,
        ]);
        if (request()->ajax()) {
            return datatables()->of($dataTahun)
                ->addColumn('Aksi', function ($dataTahun) {
                    $button = "
                    <a href='/staff/verifikasi/{$dataTahun->id}'>
                    <button type='button' id='" . $dataTahun->id . "' class='show-data btn btn-warning' >
                    Buka
                    </button>
                    </a>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.verifikasi.dataPerTahun', $array, compact('dataTahun'));
    }
    public function getDataByTahun2($tahun)
    {
        $results = DB::table('bulan')->orderBy('id', 'asc')->get();

        $array = [
            'type_menu' => 'verifikasi',
            'results' => $results,
            'tahun' => $tahun,
        ];

        if (request()->ajax()) {
            return datatables()->of($results)
                ->addColumn('Aksi', function ($row) use ($array) {
                    $button = "
                    <a href='/staff/verifikasi/{$array['tahun']}/{$row->id}'>
                    <button type='button' id='" . $row->id . "' class='show-data btn btn-warning' >
                    Buka
                    </button>
                    </a>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.verifikasi.dataPerBulan', $array);
    }
    public function getDataByInstansi($tahun, $bulan)
    {
        $results = DB::table('instansi')
            ->select('instansi.id', 'instansi.nama_instansi', 'bukti_iuran.bulan_id', 'bukti_iuran.tahun_id', 'bukti_iuran.status')
            ->orderBy('bukti_iuran.updated_at', 'asc')
            ->leftJoin('bukti_iuran', 'instansi.id', 'bukti_iuran.instansi_id')
            ->where('instansi.id', '!=', 1)
            ->where('bulan_id', '=', $bulan)
            ->get();

        $array = [
            'type_menu' => 'verifikasi',
            'tahun' => $tahun,
            'bulan' => $bulan,
            'results' => $results,
        ];
        if (request()->ajax()) {
            return datatables()->of($results)
                ->addColumn('Aksi', function ($results) {
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.verifikasi.verifikasi', $array);
    }

    public function show2(Request $request, $tahun, $bulan)
    {
        $instansi_id = $request->id;
        $data = DB::table('bukti_iuran')
            ->select('id', 'file_name', 'deskripsi', 'status')
            ->where('instansi_id', '=', $instansi_id)
            ->where('tahun_id', '=', $tahun)
            ->where('bulan_id', '=', $bulan)
            ->get();
        // dd($data);
        return response()->json(['data' => $data]);
    }
    public function verifikasi(Request $request, $tahun, $bulan)
    {
        $id = $request->id;
        $simpan = [
            'id' => $id,
            'status' => $request->status,
        ];
        $data = DB::table('bukti_iuran')
            ->select('*')
            ->where('id', '=', $id)
            ->update($simpan);
        return response()->json(['data' => $data]);
    }
}
