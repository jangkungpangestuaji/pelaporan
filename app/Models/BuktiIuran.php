<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiIuran extends Model
{
    use HasFactory;
    protected $table = 'bukti_iuran';
    protected $fillable = [
        'iuran_id',
        'file_name',
        'deskripsi',
        'status',
    ];
}
