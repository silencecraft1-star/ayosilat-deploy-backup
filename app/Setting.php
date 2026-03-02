<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $fillable =[
        'judul',
        'poll',
        'arena',
        'jadwal',
        'sesi',
        'babak',
        'biru',
        'merah',
        'keterangan',
        'juri_1',
        'juri_2',
        'juri_3',
        'juri_4',
        'juri_5',
        'juri_6',
        'juri_7',
        'juri_8',
        'time',
        'status',
        'partai'
    ];
}
