<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiBerkas extends Model
{
    use HasFactory;
    protected $table = 'bukti_iuran';
    protected $fillable = [
        'bukti_id',
        'status',
    ];
}
