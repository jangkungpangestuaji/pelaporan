<?php

namespace App\Imports;

use App\Models\DataIuran;
use App\Models\Peserta;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class DapenImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {
        $results = DB::table('data_iuran')
            ->leftJoin('peserta', 'peserta.id', '=', 'data_iuran.peserta_id')
            ->select('*')
            // ->select('peserta.id', 'peserta.instansi_id', 'peserta.no_peserta', 'peserta.nik', 'peserta.nama', 'data_iuran.nama_bulan', 'data_iuran.gaji_pokok', 'data_iuran.adj_gapok', 'data_iuran.in_peserta', 'data_iuran.rapel_in_peserta', 'data_iuran.in_pk', 'data_iuran.rapel_in_pk', 'data_iuran.jumlah')
            ->get();
        // dd($results);

        $peserta_id = "";
        $data = [];
        foreach ($rows as $row) {
            for ($n = 0; $n < count($results); $n++) {
                if ($results[$n]->no_peserta == $row[2]) {
                    $peserta_id = $results[$n]->id; // Menggunakan operator penugasan =
                    // Lakukan sesuatu dengan $peserta_id jika diperlukan
                } else {
                    $peserta[] = array(
                        'instansi_id' => $row[0],
                        'no_peserta' => $row[0],
                        'nik' => $row[0],
                        'nama' => $row[0],
                    );
                    Peserta::insert($peserta);
                }
            }
        }

        foreach ($rows as $key) {
            $data[] = array(
                'peserta_id' => $peserta_id,
                'nama_bulan' => $key[3],
                'gaji_pokok' => $key[4],
                'adj_gapok' => $key[5],
                'in_peserta' => $key[6],
                'rapel_in_peserta' => $key[7],
                'in_pk' => $key[8],
                'rapel_in_pk' => $key[8],
                'jumlah' => $key[9],
            );
        }
        // dd($data);
        DataIuran::insert($data);
    }
}
