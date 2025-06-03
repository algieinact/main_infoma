@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Profil Saya</h1>

        <!-- Avatar Section -->
        <div class="flex justify-center mb-8">
            <img src="{{ Auth::user()->avatar_url }}" alt="Avatar"
                class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-md">
        </div>

        <!-- User Information -->
        <div class="space-y-6">
            <!-- Personal Information -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <p class="text-gray-900 font-medium">{{ Auth::user()->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                        <p class="text-gray-900">{{ Auth::user()->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nomor Telepon</label>
                        <p class="text-gray-900">{{ Auth::user()->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                        <p class="text-gray-900">
                            @if(Auth::user()->gender === 'male')
                            Laki-laki
                            @elseif(Auth::user()->gender === 'female')
                            Perempuan
                            @else
                            -
                            @endif
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                        <p class="text-gray-900">{{ Auth::user()->address ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                        <p class="text-gray-900">
                            {{ Auth::user()->birth_date ? Auth::user()->birth_date->format('d F Y') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Education Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pendidikan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Universitas</label>
                        <p class="text-gray-900">{{ Auth::user()->university ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Jurusan</label>
                        <p class="text-gray-900">{{ Auth::user()->major ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tahun Lulus</label>
                        <p class="text-gray-900">{{ Auth::user()->graduation_year ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Button -->
        <div class="flex justify-center mt-8">
            <a href="{{ route('profile.edit') }}"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                Edit Profil
            </a>
        </div>
    </div>
</div>
@endsection