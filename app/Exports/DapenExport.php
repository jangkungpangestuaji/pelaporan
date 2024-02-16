<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class DapenExport implements FromCollection
{
    protected $id;
    function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        $results = DB::table('data_iuran')
            ->leftJoin('peserta', 'peserta.id', '=', 'data_iuran.peserta_id')
            ->select('peserta.no_peserta', 'peserta.nik', 'peserta.nama', 'data_iuran.nama_bulan', 'data_iuran.gaji_pokok', 'data_iuran.adj_gapok', 'data_iuran.in_peserta', 'data_iuran.rapel_in_peserta', 'data_iuran.in_pk', 'data_iuran.rapel_in_pk', 'data_iuran.jumlah')
            ->where('peserta.instansi_id', '=', $this->id)
            ->get();
        return $results;
    }
}
