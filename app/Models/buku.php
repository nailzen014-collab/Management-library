<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class buku extends Model
{
    protected $fillable = [
        'judul',
        'stock',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(author::class);
    }

    public function kategoris()
    {
        return $this->belongsToMany(
            kategori::class,
            'buku_kategoris',
            'buku_id',
            'kategori_id'
        );
    }
}