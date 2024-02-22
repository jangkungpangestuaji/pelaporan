<?php

namespace App\Http\Controllers;

use App\Imports\DapenImport;
use App\Models\BuktiIuran;
use Illuminate\Http\Request;
use App\Models\Tahun;
use App\Models\DataIuran;
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
        $data = DB::table('bulan')->get();
        $status = DB::table('bukti_iuran')->select('iuran_id')->where('iuran_id', '=', date('m'))
            ->exists();
        // dd($status);
        $array = [
            'data' => $data,
            'status' => $status,
            'id' => $id
        ];

        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('Aksi', function ($row) use ($array) {
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }

        return view('pages.mitra.upload.dataPerBulan', $array, compact('data'));
    }
    public function upload(Request $request, $tahun, $bulan)
    {
        $status = DB::table('bukti_iuran')->select('iuran_id')->where('iuran_id', '=', date('m'))
            ->exists();
        $iuran_id = DB::table('data_iuran')
            ->select('data_iuran.id')
            ->where('data_iuran.tahun_id', '=', $tahun)
            ->where('data_iuran.bulan_id', '=', $bulan)
            ->get();

        $request->validate([
            'file_name' => 'required|file', // Validasi bahwa input adalah file
            'deskripsi' => 'required', // Validasi bahwa deskripsi harus ada
        ]);

        $cek = DB::table('bukti_iuran')->select('iuran_id')->where('iuran_id', '=', $iuran_id[0]->id)
            ->exists();

        if (!$cek) {
            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');
                // $fileName = $file->getClientOriginalName();
                $fileName = Str::uuid().'.pdf';
                $data = [
                    'iuran_id' => $iuran_id[0]->id,
                    'file_name' => $fileName,
                    'deskripsi' => $request->deskripsi,
                ];

                $upload = BuktiIuran::insert($data);
                $file->move(public_path('uploads'), $fileName);

                return response()->json(['month' => $bulan]);
            } else {
                return redirect()->back()->with('error', 'Mohon dicoba kembali');
            }
        } else {
            return redirect()->back()->with('error', 'File telah diupload sebelumnya');
        }
    }
    public function show(Request $request, $tahun, $bulan)
    {
        // $id = $request->id;
        $data = DB::table('bukti_iuran')
            ->select('bukti_iuran.id','bukti_iuran.file_name', 'bukti_iuran.deskripsi')
            ->leftJoin('data_iuran', 'bukti_iuran.iuran_id', '=', 'data_iuran.id')
            ->where('data_iuran.tahun_id', '=', $tahun)
            ->where('data_iuran.bulan_id', '=', $bulan)
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
        if($request->hasFile('file_name')){
            $file = $request->file('file_name');
            $fileName = Str::uuid().'.pdf';
            $update = [
                'file_name' => $fileName,
                'deskripsi' => $request->deskripsi,
            ];
            $data = BuktiIuran::find($id);
            $data->update($update);
            $data->save();
            if($data){
                $file->move(public_path('uploads'), $fileName);
            }
            return response()->json(['data' => $data]);
        }
    }
}