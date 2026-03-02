<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entry extends Model
{
    use HasFactory;
    protected $table = 'entries';
    protected $fillable = [
        'id',
        'arena',
        'partai',
        'merah',
        'biru'
    ];
}
