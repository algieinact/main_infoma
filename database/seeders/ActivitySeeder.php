<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        Activity::create([
            'provider_id' => 7,
            'category_id' => 2, // Seminar category
            'title' => 'Seminar Teknologi IoT',
            'slug' => 'seminar-teknologi-iot',
            'description' => 'Seminar tentang perkembangan teknologi IoT dan implementasinya',
            'type' => 'seminar',
            'price' => 30000,
            'is_free' => false,
            'location' => 'Auditorium Telkom University',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'format' => 'offline',
            'meeting_link' => null,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(7)->addHours(3),
            'registration_deadline' => now()->addDays(5),
            'requirements' => ['Mahasiswa aktif'],
            'benefits' => ['Sertifikat', 'Snack', 'Souvenir'],
            'images' => [],
            'max_participants' => 100,
            'current_participants' => 0,
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Activity::create([
            'provider_id' => 4,
            'category_id' => 3,
            'title' => 'Webinar Kesehatan Mental',
            'slug' => 'webinar-kesehatan-mental',
            'description' => 'Webinar tentang pentingnya kesehatan mental bagi mahasiswa',
            'type' => 'webinar',
            'price' => 0,
            'is_free' => true,
            'location' => 'Online', // diperbaiki dari null ke 'Online'
            'city' => 'Online',
            'province' => 'DKI Jakarta',
            'format' => 'online',
            'meeting_link' => 'https://zoom.us/meeting',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(5)->addHours(2),
            'registration_deadline' => now()->addDays(3),
            'requirements' => ['Mahasiswa aktif'],
            'benefits' => ['Sertifikat', 'E-Certificate'],
            'images' => [],
            'max_participants' => 200,
            'current_participants' => 0,
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Activity::create([
            'provider_id' => 4,
            'category_id' => 4, // Lomba category
            'title' => 'Lomba Coding Competition',
            'slug' => 'lomba-coding-competition',
            'description' => 'Lomba coding untuk mahasiswa se-Bandung',
            'type' => 'lomba',
            'price' => 100000,
            'is_free' => false,
            'location' => 'Lab Komputer Telkom University',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'format' => 'offline',
            'meeting_link' => null,
            'start_date' => now()->addDays(14),
            'end_date' => now()->addDays(14)->addHours(6),
            'registration_deadline' => now()->addDays(10),
            'requirements' => ['Mahasiswa aktif'],
            'benefits' => ['Sertifikat', 'Snack', 'T-Shirt', 'Hadiah'],
            'images' => [],
            'max_participants' => 50,
            'current_participants' => 0,
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Activity::create([
            'provider_id' => 4,
            'category_id' => 3,
            'title' => 'Workshop UI/UX Design',
            'slug' => 'workshop-ui-ux-design',
            'description' => 'Workshop tentang dasar-dasar UI/UX Design',
            'type' => 'workshop',
            'price' => 75000,
            'is_free' => false,
            'location' => 'Ruang Workshop Telkom University',
            'city' => 'Bandung',
            'province' => 'Jawa Timur',
            'format' => 'offline',
            'meeting_link' => null,
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(10)->addHours(4),
            'registration_deadline' => now()->addDays(7),
            'requirements' => ['Mahasiswa aktif'],
            'benefits' => ['Sertifikat', 'Snack', 'Template Design'],
            'images' => [],
            'max_participants' => 30,
            'current_participants' => 0,
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => false,
        ]);
    }
}