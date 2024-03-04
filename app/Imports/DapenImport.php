<?php

namespace App\Imports;

use App\Models\DataIuran;
use App\Models\Peserta;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class DapenImport implements ToCollection
{
    protected $instansi_id;
    protected $tahun;
    protected $bulan;
    public function __construct($tahun, $bulan)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }
    public function collection(Collection $rows)
    {
        $instansi_id = Auth::user()->instansi_id;
        $peserta = DB::table('peserta')
            ->where('instansi_id', '=', $instansi_id)
            ->get();

        $firstRowSkipped = false;
        foreach ($rows as $row) {
            if (!$firstRowSkipped) {
                $firstRowSkipped = true;
                continue; // Lewati iterasi jika ini adalah baris header
            }

            $existingPeserta = $peserta->where('no_peserta', $row[1])->first();

            if ($existingPeserta) {
                $peserta_id = $existingPeserta->id;
            } else {
                // $newPeserta = new Peserta;
                $newPeserta = [
                    'instansi_id' => $instansi_id,
                    'no_peserta' => $row[1],
                    'nik' => $row[2],
                    'nama' => $row[0],
                ];
                $peserta_id = Peserta::insertGetId($newPeserta);
            }

            $data[] = [
                'peserta_id' => $peserta_id,
                'tahun_id' => $this->tahun,
                'bulan_id' => $this->bulan,
                'gaji_pokok' => $row[3],
                'adj_gapok' => $row[3],
                'in_peserta' => $row[3],
                'rapel_in_peserta' => $row[3],
                'in_pk' => $row[3],
                'rapel_in_pk' => $row[3],
                'jumlah' => $row[3],
            ];
        }
        DataIuran::insert($data);
    }
}
