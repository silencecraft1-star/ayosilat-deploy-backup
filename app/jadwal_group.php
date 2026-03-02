<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal_group extends Model
{
    use HasFactory;
    protected $table = 'jadwal_groups';
    protected $fillable =[
        'id',
        'id_sesi',
        'id_poll',
        'partai',
        'kelas',
        'merah',
        'biru',
        'score_biru',
        'score_merah',
        'deviasi_biru',
        'deviasi_merah',
        'timer_biru',
        'timer_merah',
        'pemenang',
        'kondisi', 
        'arena',
        'tipe',
        'keterangan',
        'status'
    ];
}
