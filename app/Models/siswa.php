<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    protected $fillable = [
        'nama',
        'nis',
        'kelas_id',
    ];

    public function kelas () {

    return $this->belongsTo(kelas::class);
    //pakai belongsTO karna ada banyak siswa yang masuk dalam satu kelas
    
}
}


