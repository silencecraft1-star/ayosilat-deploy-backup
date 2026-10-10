<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable =[
        'name',
        'status',
        'keterangan',
        'perbedaan_poin',
        'is_wmp',
    ];

    protected $casts = [
        'is_wmp' => 'boolean',
    ];
}
