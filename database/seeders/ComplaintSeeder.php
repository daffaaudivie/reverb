<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Complaint;
use App\Enums\Status;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Complaint::create([
            'title' => 'Test Complaint',
            'description' => 'This is a test complaint',
            'user_id' => 1,
            'category_id' => 1,
        ]);

        Complaint::create([
            'title' => 'Test Complaint 002',
            'description' => 'Lorem Ipsum Sit Amet Consectetur Adipiscing Elit
            Mauris Bibendum Ipsum Nunc Convallis Nisl Nisl Nibh Nibh
            Eget Mattis Egestas',
            'user_id' => 1,
            'category_id' => 2,
        ]);
    }
}
