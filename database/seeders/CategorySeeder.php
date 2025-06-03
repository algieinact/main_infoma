<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Residence Categories
        Category::create([
            'name' => 'Kost Putra',
            'slug' => 'kost-putra',
            'description' => 'Kost khusus untuk mahasiswa putra',
            'type' => 'residence',
            'icon' => 'male',
            'color' => '#2563eb',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Kost Putri',
            'slug' => 'kost-putri',
            'description' => 'Kost khusus untuk mahasiswa putri',
            'type' => 'residence',
            'icon' => 'female',
            'color' => '#d946ef',
            'is_active' => true,
        ]);

        // Activity Categories
        Category::create([
            'name' => 'Seminar',
            'slug' => 'seminar',
            'description' => 'Kegiatan seminar dan workshop',
            'type' => 'activity',
            'icon' => 'chalkboard-teacher',
            'color' => '#f59e42',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Lomba',
            'slug' => 'lomba',
            'description' => 'Berbagai jenis lomba kampus',
            'type' => 'activity',
            'icon' => 'trophy',
            'color' => '#22c55e',
            'is_active' => true,
        ]);
    }
}