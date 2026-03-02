<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontigenModel extends Model
{
    use HasFactory;
    protected $table = 'kontigens';
    protected $fillable =[
        'kontigen',
        'manager',
        'official',
        'hp',
        'provinsi',
        'kota',
        'kecamatan',
        'desa',
        'alamat',
        'id_user',
        'status',
        'keterangan'
    ];
}
