<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class author extends Model
{
    protected $fillable = [
        'nama_author'
    ];

    public function buku () {

        return $this->hasMany(buku::class);
        //pakai hasMany karna ada banyak buku yang ditulis oleh satu author
        
    }
}
