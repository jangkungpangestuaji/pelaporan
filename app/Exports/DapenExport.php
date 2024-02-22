<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DapenExport implements FromCollection, WithHeadings
{
    protected $instansi_id;
    protected $tahun;
    protected $bulan;

    public function __construct($instansi_id, $tahun, $bulan)
    {
        $this->instansi_id = $instansi_id;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function collection()
    {
        $results = DB::table('data_iuran')
            ->leftJoin('peserta', 'peserta.id', '=', 'data_iuran.peserta_id')
            ->leftJoin('instansi', 'instansi.id', '=', 'peserta.instansi_id')
            ->leftJoin('tahun', 'tahun.id', '=', 'data_iuran.tahun_id')
            ->leftJoin('bulan', 'bulan.id', '=', 'data_iuran.bulan_id')
            ->select(
                'peserta.nama',
                'peserta.no_peserta',
                'peserta.nik',
                'data_iuran.gaji_pokok',
                'data_iuran.adj_gapok',
                'data_iuran.in_peserta',
                'data_iuran.rapel_in_peserta',
                'data_iuran.in_pk',
                'data_iuran.rapel_in_pk',
                'data_iuran.jumlah'
            )
            ->where('peserta.instansi_id', '=', $this->instansi_id)
            ->where('data_iuran.tahun_id', '=', $this->tahun)
            ->where('data_iuran.bulan_id', '=', $this->bulan)
            ->get();

        return $results;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'No. Peserta',
            'NIK',
            'Gaji Pokok',
            'Adj. Gapok',
            'IN Peserta',
            'Rapel IN Peserta',
            'IN PK',
            'Rapel IN PK',
            'Jumlah',
        ];
    }
}
