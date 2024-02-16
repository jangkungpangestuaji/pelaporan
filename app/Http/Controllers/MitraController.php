<?php

namespace App\Http\Controllers;

use App\Imports\DapenImport;
use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\DataIuran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MitraController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }
    public function index()
    {
        $type_menu = ([
            'type_menu' => 'mitraDataPesertaPensiun',
        ]);
        $instansi_id = Auth::user()->instansi_id;
        $data = Peserta::orderBy('id', 'desc')->where('instansi_id', '=', $instansi_id)->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('Aksi', function ($data) {
                    $button = "
                <button type='button' id='" . $data->id . "' class='update btn btn-warning'  >
                    <i class='fas fa-edit'></i>
                </button>";

                    $button .= "
                <button type='button' id='" . $data->id . "' class='destroy btn btn-danger'>
                    <i class='fas fa-times'></i>
                </button>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.mitra.DataPesertaPensiun', $type_menu, compact('data'));
    }
    public function index_2()
    {
        $instansi_id = Auth::user()->instansi_id;
        $dataPeserta = Peserta::orderBy('id', 'desc')->where('instansi_id', '=', $instansi_id)->get();
        $dataBulan = DB::table('data_iuran')
            ->leftJoin('peserta', 'peserta.id', '=', 'data_iuran.peserta_id')
            ->select('peserta.id', 'peserta.instansi_id', 'peserta.no_peserta', 'peserta.nik', 'peserta.nama', 'data_iuran.nama_bulan', 'data_iuran.gaji_pokok', 'data_iuran.adj_gapok', 'data_iuran.in_peserta', 'data_iuran.rapel_in_peserta', 'data_iuran.in_pk', 'data_iuran.rapel_in_pk', 'data_iuran.jumlah')
            ->where('peserta.instansi_id', '=', $instansi_id)
            ->get();

        $array = ([
            'type_menu' => 'mitraDataPesertaPensiunPerBulan',
            'dataBulan' => $dataBulan,
            'dataPeserta' => $dataPeserta
        ]);

        if (request()->ajax()) {
            return datatables()->of($dataBulan)
                ->addColumn('Aksi', function ($dataBulan) {
                    $button = "
                <button type='button' id='" . $dataBulan->id . "' class='update btn btn-warning'  >
                    <i class='fas fa-edit'></i>
                </button>";

                    $button .= "
                <button type='button' id='" . $dataBulan->id . "' class='destroy btn btn-danger'>
                    <i class='fas fa-times'></i>
                </button>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.mitra.dataPesertaPensiunPerBulan', $array, compact('dataBulan'));
    }
    public function store_2(Request $request)
    {
        $request->validate(
            [
                'peserta_id' => 'required',
                'nama_bulan' => 'required',
                'gaji_pokok' => 'required',
                'adj_gapok' => 'required',
                'in_peserta' => 'required',
                'rapel_in_peserta' => 'required',
                'in_pk' => 'required',
                'rapel_in_pk' => 'required',
                'jumlah' => 'required',
            ]
        );
        $data = new DataIuran;
        $data->peserta_id = $request->peserta_id;
        $data->nama_bulan = $request->nama_bulan;
        $data->gaji_pokok = $request->gaji_pokok;
        $data->adj_gapok = $request->adj_gapok;
        $data->in_peserta = $request->in_peserta;
        $data->rapel_in_peserta = $request->rapel_in_peserta;
        $data->in_pk = $request->in_pk;
        $data->rapel_in_pk = $request->rapel_in_pk;
        $data->jumlah = $request->jumlah;
        $data->save();
    }
    public function show_2(Request $request)
    {
        $id = $request->id;
        $data = DataIuran::find($id);
        return response()->json(['data' => $data]);
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'instansi_id' => 'required',
                'no_peserta' => 'required',
                'nik' => 'required',
                'nama' => 'required',
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong',
                'no_peserta.required' => 'No Peserta tidak boleh kosong',
            ]
        );

        $data = new Peserta;
        $data->instansi_id = $request->instansi_id;
        $data->no_peserta = $request->no_peserta;
        $data->nik = $request->nik;
        $data->nama = $request->nama;
        $data->save();
    }
    public function show(Request $request)
    {
        $id = $request->id;
        $data = Peserta::find($id);
        return response()->json(['data' => $data]);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $update = [
            'no_peserta' => $request->no_peserta,
            'nik' => $request->nik,
            'nama' => $request->nama,
        ];

        $data = Peserta::find($id);
        $data->update($update);
        $data->save();
        return response()->json(['data' => $data]);
    }
    public function update_2(Request $request)
    {
        $id = $request->id;
        $update = [
            'peserta_id' => $request->peserta_id,
            'nama_bulan' => $request->nama_bulan,
            'gaji_pokok' => $request->gaji_pokok,
            'adj_gapok' => $request->adj_gapok,
            'in_peserta' => $request->in_peserta,
            'rapel_in_peserta' => $request->rapel_in_peserta,
            'in_pk' => $request->in_pk,
            'rapel_in_pk' => $request->rapel_in_pk,
            'jumlah' => $request->jumlah,
        ];

        $data = DataIuran::find($id);
        $data->update($update);
        $data->save();
        return response()->json(['data' => $data]);
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
        $data = Peserta::find($id);
        $data->delete();

        return response()->json(['data' => $data]);
    }
    public function destroy_2(Request $request)
    {
        $id = $request->id;
        $data = DataIuran::find($id);
        $data->delete();

        return response()->json(['data' => $data]);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls', // Validasi tipe file
        ]);
        $file = $request->file('file');
        // dd($file);
        Excel::import(new DapenImport, $file);

        return redirect()->back()->with('success', 'All good!');
    }
}
