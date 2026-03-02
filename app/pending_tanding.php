<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pending_tanding extends Model
{
    use HasFactory;
    protected $table = 'pending_tandings';
    protected $fillable =[
        'score',
        'juri1',
        'juri2',
        'juri3',
        'partai',
        'isValid',
        'id_sesi',
        'id_perserta',
        'babak',
        'arena',
        'status',
        'keterangan'
    ];
}
