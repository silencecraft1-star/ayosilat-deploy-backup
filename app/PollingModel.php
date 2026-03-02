<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingModel extends Model
{
    use HasFactory;
    protected $table = 'pollings';
    protected $fillable =[
        'id',
        'id_arena',
        'name',
        'keterangan'
    ];
}
