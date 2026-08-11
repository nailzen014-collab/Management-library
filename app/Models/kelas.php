<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kelas extends Model
{
protected $table = 'kelas';
    protected $fillable = [
        'nama_kelas',
        'tingkat',
    ];
    public function siswa () {

        return $this->hasMany(siswa::class);
        //pakai hasMany karna ada banyak siswa yang masuk dalam satu kelas
        
    }
}
