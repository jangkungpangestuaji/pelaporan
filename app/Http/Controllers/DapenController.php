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
        $dataInstansi = DB::table('instansi')
            ->orderBy('id', 'asc')
            ->where('id', '!=', '1')
            ->get();
        $array = ([
            'type_menu' => 'dataPesertaPensiun',
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
                    <button type='button' id='" . $dataInstansi->id . "' class='show-data btn btn-primary' >
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
    public function getDataByInstansi($id)
    {
        $currentYear = date('Y');
        if (!Tahun::where('tahun', $currentYear)->exists()) {
            // Tambahkan data tahun baru
            Tahun::insert(['tahun' => $currentYear]);
        }
        $dataTahun = DB::table('tahun')->orderBy('tahun', 'asc')->get();
        $array = ([
            'instansi_id' => $id,
            'type_menu' => 'dataPesertaPensiun',
            'dataTahun' => $dataTahun
        ]);
        if (request()->ajax()) {
            return datatables()->of($dataTahun)
                ->addColumn('Aksi', function ($row) use ($array) {
                    $button = "
                    <a href='/staff/dataPesertaPensiun/{$array['instansi_id']}/{$row->id}'>
                    <button type='button' id='" . $row->id . "' class='update btn btn-primary mb-2' >
                    Buka
                    </button>
                    </a>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.dataPerTahun', $array);
    }
    public function getDataByTahun($id, $tahun)
    {
        $results = DB::table('bulan')
            ->select('*')
            ->get();

        $array = [
            'type_menu' => 'dataPesertaPensiun',
            'results' => $results,
            'tahun' => $tahun,
            'instansi_id' => $id
        ];
        // dd($results);

        if (request()->ajax()) {
            return datatables()->of($results)
                ->addColumn('Aksi', function ($row) use ($array) {
                    $button = "
                    <a href='/staff/dataPesertaPensiun/{$array['instansi_id']}/{$array['tahun']}/{$row->id}'>
                    <button type='button' id='" . $row->id . "' class='update btn btn-primary mb-2' >
                    Buka
                    </button>
                    </a>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.dataPerBulan', $array);
    }
    public function getDataByBulan($id, $tahun, $bulan)
    {
        $results = DB::table('data_iuran')
            ->leftJoin('peserta', 'peserta.id', '=', 'data_iuran.peserta_id')
            ->leftJoin('instansi', 'instansi.id', '=', 'peserta.instansi_id')
            ->leftJoin('tahun', 'tahun.id', '=', 'data_iuran.tahun_id')
            ->leftJoin('bulan', 'bulan.id', '=', 'data_iuran.bulan_id')
            ->select('*')
            ->where('peserta.instansi_id', '=', $id)
            ->where('data_iuran.tahun_id', '=', $tahun)
            ->where('data_iuran.bulan_id', '=', $bulan)
            ->get();

        $array = [
            'type_menu' => 'data-pensiun',
            'results' => $results,
            'instansi_id' => $id,
            'tahun' => $tahun,
            'bulan' => $bulan,
        ];
        // dd($results);

        if (request()->ajax()) {
            return datatables()->of($results)
                ->addColumn('Aksi', function ($row) use ($array) {
                    $button = "
                    <a href='/staff/dataPesertaPensiun/{$array['instansi_id']}/{$array['tahun']}/{$array['bulan']}/{$row->id}'>
                    <button type='button' id='" . $row->id . "' class='update btn btn-warning mb-2' >
                    Buka
                    </button>
                    </a>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.dapen.dataPensiunn', $array);
    }
    public function export_excel($instansi_id, $tahun, $bulan)
    {
        return Excel::download(new DapenExport($instansi_id, $tahun, $bulan), 'dapen.xlsx');
    }
    public function show(Request $request)
    {
        $id = $request->id;
        $data = DataIuran::find($id);
        return response()->json(['data' => $data]);
    }
}
