<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwals';
    protected $fillable =[
        'perserta_biru',
        'perserta_merah',
        'arena',
        'score_merah',
        'score_biru',
        'keterangan',
        'status',
        'menang'
    ];
}
