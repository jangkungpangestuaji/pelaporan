<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataIuran extends Model
{
    use HasFactory;

    protected $table = 'data_iuran';

    protected $fillable = [
        'peserta_id',
        'gaji_pokok',
        'adj_gapok',
        'in_peserta',
        'rapel_in_peserta',
        'in_pk',
        'rapel_in_pk',
        'jumlah',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }
}
