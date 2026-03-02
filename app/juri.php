<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class juri extends Model
{
    use HasFactory;
    protected $table = 'juris';
    protected $fillable =[
        'name',
        'alamat',
        'nomor_hp',
        'keterangan',
        'status'
    ];
}
