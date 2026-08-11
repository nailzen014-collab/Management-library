<?php

namespace Database\Seeders;

use App\Models\buku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SyncBukuKategoriSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sync kategori dari kolom kategori_id ke tabel buku_kategoris
        $bukus = buku::all();
        
        foreach ($bukus as $buku) {
            if ($buku->kategori_id) {
                // Sync kategori ke pivot table
                $buku->kategoris()->sync([$buku->kategori_id], false);
            }
        }
        
        $this->command->info('Kategori buku berhasil di-sync!');
    }
}
