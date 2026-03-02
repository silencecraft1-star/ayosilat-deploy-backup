<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersertaModel extends Model
{
    use HasFactory;
    protected $table = 'persertas';
    protected $fillable =[
        'id',
        'id_perserta',
        'id_kontigen',
        'gender',
        'usia_category',
        'berat_badan',
        'tinggi_badan',
        'category',
        'kelas',
        'name',
        'status'
    ];

    // Define relationships
    public function kontigen()
    {
        return $this->belongsTo(KontigenModel::class, 'id_kontigen', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'kelas', 'id');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category', 'id');
    }
}
