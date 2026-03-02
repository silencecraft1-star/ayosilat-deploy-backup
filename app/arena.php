<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class arena extends Model
{
    use HasFactory;
    protected $table = 'arenas';
    protected $fillable = [
        'name',
        'status',
        'keterangan',
        'juri'
    ];
}
