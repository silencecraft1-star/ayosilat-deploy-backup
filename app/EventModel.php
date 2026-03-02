<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventModel extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable =[
        'name',
        'tanggal_mulai',
        'tanggal_selesai',
        'max_perserta',
        'status',
        'catatan',
        'img'
    ];
}
