<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Residence;

class ResidenceSeeder extends Seeder
{
    public function run()
    {
        Residence::create([
            'provider_id' => 7,
            'category_id' => 1,
            'title' => 'Kost Putra Telkom',
            'slug' => 'kost-putra-telkom',
            'description' => 'Kost putra dengan fasilitas lengkap dekat Telkom University',
            'type' => 'kost',
            'price' => 1500000,
            'price_period' => 'monthly',
            'address' => 'Jl. Telekomunikasi No. 1, Bandung',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'latitude' => null,
            'longitude' => null,
            'facilities' => ['Wifi', 'Kamar Mandi Dalam', 'AC', 'Kasur', 'Lemari'],
            'rules' => ['Tidak boleh merokok', 'Tidak boleh membawa tamu malam'],
            'images' => [],
            'total_rooms' => 5,
            'available_rooms' => 5,
            'gender_type' => 'male',
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => true,
            'available_from' => now(),
        ]);

        Residence::create([
            'provider_id' => 7,
            'category_id' => 2,
            'title' => 'Kost Putri Telkom',
            'slug' => 'kost-putri-telkom',
            'description' => 'Kost putri dengan fasilitas lengkap dekat Telkom University',
            'type' => 'kost',
            'price' => 1800000,
            'price_period' => 'monthly',
            'address' => 'Jl. Telekomunikasi No. 2, Bandung',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'latitude' => null,
            'longitude' => null,
            'facilities' => ['Wifi', 'Kamar Mandi Dalam', 'AC', 'Kasur', 'Lemari', 'Dapur'],
            'rules' => ['Tidak boleh merokok', 'Tidak boleh membawa tamu malam'],
            'images' => [],
            'total_rooms' => 3,
            'available_rooms' => 3,
            'gender_type' => 'female',
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => true,
            'available_from' => now(),
        ]);

        Residence::create([
            'provider_id' => 7,
            'category_id' => 1,
            'title' => 'Kontrakan Putra',
            'slug' => 'kontrakan-putra',
            'description' => 'Kontrakan untuk mahasiswa putra dengan fasilitas lengkap',
            'type' => 'kontrakan',
            'price' => 2500000,
            'price_period' => 'monthly',
            'address' => 'Jl. Telekomunikasi No. 3, Bandung',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'latitude' => null,
            'longitude' => null,
            'facilities' => ['Wifi', 'Kamar Mandi Dalam', 'AC', 'Kasur', 'Lemari', 'Dapur', 'Ruang Tamu'],
            'rules' => ['Tidak boleh merokok', 'Tidak boleh membawa tamu malam'],
            'images' => [],
            'total_rooms' => 2,
            'available_rooms' => 2,
            'gender_type' => 'male',
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => false,
            'available_from' => now(),
        ]);

        Residence::create([
            'provider_id' => 7,
            'category_id' => 2,
            'title' => 'Kontrakan Putri',
            'slug' => 'kontrakan-putri',
            'description' => 'Kontrakan untuk mahasiswa putri dengan fasilitas lengkap',
            'type' => 'kontrakan',
            'price' => 2800000,
            'price_period' => 'monthly',
            'address' => 'Jl. Telekomunikasi No. 4, Bandung',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'latitude' => null,
            'longitude' => null,
            'facilities' => ['Wifi', 'Kamar Mandi Dalam', 'AC', 'Kasur', 'Lemari', 'Dapur', 'Ruang Tamu'],
            'rules' => ['Tidak boleh merokok', 'Tidak boleh membawa tamu malam'],
            'images' => [],
            'total_rooms' => 2,
            'available_rooms' => 2,
            'gender_type' => 'female',
            'rating' => 0,
            'total_reviews' => 0,
            'is_active' => true,
            'is_featured' => false,
            'available_from' => now(),
        ]);
    }
}