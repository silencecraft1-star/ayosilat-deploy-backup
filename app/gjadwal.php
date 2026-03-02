<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal2 extends Model
{
    use HasFactory;
    protected $table = 'jadwal_group';
    protected $fillable =[
        'partai',
        'kelas',
        'merah',
        'biru',
        'skor_biru',
        'skor_merah',
        'pemenang',
        'kondisi', 
        'status'
    ];
}
