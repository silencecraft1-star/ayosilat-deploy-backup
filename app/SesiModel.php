<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiModel extends Model
{
    use HasFactory;
    protected $table = 'sesi';
    protected $fillable =[
        'id',
        'id_arena',
        'name',
        'keterangan'
    ];
}
