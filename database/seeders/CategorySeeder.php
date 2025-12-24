<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
           'name' => 'Sandang & Pangan',
           'description' => 'Kategori ini digunakan untuk menyimpan informasi aduan tentang sandang dan pangan.',
        ],
        [
            'name' => 'Bencana Alam',
            'description' => 'Kategori ini digunakan untuk menyimpan informasi aduan tentang bencana alam.',
        ],
        [
            'name' => 'Pertanian',
            'description' => 'Kategori ini digunakan untuk menyimpan informasi aduan tentang pertanian.',
        ]
    );
    }

}
