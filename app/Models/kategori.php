<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    protected $fillable = [
        'nama_kategori',
    ];

    public function buku()
    {
        return $this->belongsToMany(
            buku::class,
            'buku_kategoris',
            'kategori_id',
            'buku_id'
        );
    }
}