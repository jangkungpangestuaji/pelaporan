<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataIuran;
use App\Models\Tahun;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DapenExport;
use Illuminate\Support\Facades\DB;

class DapenController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }
    public function index()
    {
        $dataInstansi = DB::table('instansi')->orderBy('id', 'asc')->where('id', '!=', '1')->get();
        $array = ([
            'type_menu' => 'data-pensiun',
            'dataInstansi' => $dataInstansi,
        ]);
        if (request()->ajax()) {
            // $dataInstansi->id[0] == 2;
            // dd($dataInstansi);
            return datatables()->of($dataInstansi)
                ->addColumn('Aksi', function ($dataInstansi) {
                    // dd($dataInstansi);
                    $button = "
                    <a href='/staff/dataPesertaPensiun/{$dataInstansi->id}'>
                    <button type='button' id='" . $dataInstansi->id . "' class='show-data btn btn-warning' >
                    Buka
                    </button>
                    </a>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.index', $array, compact('dataInstansi'));
    }
    public function getTahun()
    {
        $dataTahun = DB::table('tahun')->orderBy('tahun', 'asc')->get();
        $array = ([
            'type_menu' => 'kelola-tahun',
            'dataTahun' => $dataTahun
        ]);
        if (request()->ajax()) {
            return datatables()->of($dataTahun)
                ->addColumn('Aksi', function ($dataTahun) {
                    // dd($dataInstansi);
                    $button = "
                    <button type='button' id='" . $dataTahun->id . "' class='update btn btn-warning mb-2' >
                    Edit
                    </button>
                    <button type='button' id='" . $dataTahun->id . "' class='destroy btn btn-danger mb-2' >
                    Hapus
                    </button>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.tahun', $array, compact('dataTahun'));
    }
    public function store(Request $request){
        $request->validate(
            [
                'tahun' => 'required',
            ],
        );
        $data = new Tahun;
        $data->tahun = $request->tahun;
        $data->save();
    }
    public function show_2(Request $request){
        $id = $request->id;

        $data = Tahun::find($id);
        return response()->json(['data' => $data]);
    }
    public function update(Request $request){
        $id = $request->id;

        $data = Tahun::find($id);
        $data->save();
        return response()->json(['data' => $data]);
    }
    public function destroy(Request $request){
        $id = $request->id;

        $data = Tahun::find($id);
        $data->delete();
        return response()->json(['data' => $data]);
    }
    public function getById($id)
    {
        $results = DB::table('bulan_per_nama')
            ->leftJoin('peserta', 'peserta.id', '=', 'bulan_per_nama.peserta_id')
            ->select('peserta.id', 'peserta.instansi_id', 'peserta.no_peserta', 'peserta.nik', 'peserta.nama', 'bulan_per_nama.nama_bulan', 'bulan_per_nama.gaji_pokok', 'bulan_per_nama.adj_gapok', 'bulan_per_nama.in_peserta', 'bulan_per_nama.rapel_in_peserta', 'bulan_per_nama.in_pk', 'bulan_per_nama.rapel_in_pk', 'bulan_per_nama.jumlah')
            ->where('peserta.instansi_id', '=', $id)
            ->get();

        $array = [
            'type_menu' => 'data-pensiun',
            'results' => $results,
            'id' => $id
        ];

        if (request()->ajax()) {
            return datatables()->of($results)
                ->addColumn('Aksi', function ($results) {
                    $button = "
                <button type='button' id='" . $results->id . "' class='show-data btn btn-warning' data-toggle='modal' data-target='#modalDetail'>
                <!--  
                <i class='fas fa-edit'></i>
                -->
                    Detail
                </button>";

                    $button .= "
                    <!--  
                    <button type='button' id='" . $results->id . "' class='destroy btn btn-danger'>
                    <i class='fas fa-times'></i>
                    </button>
                    -->";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.dataPensiun', $array);
    }
    public function export_excel($id)
    {
        return Excel::download(new DapenExport($id), 'dapen.xlsx');
    }
    public function show(Request $request)
    {
        $id = $request->id;
        $data = DataIuran::find($id);
        return response()->json(['data' => $data]);
    }
}
