<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Infoma - Informasi Kebutuhan Mahasiswa')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
    [x-cloak] {
        display: none !important;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .tab-button.active {
        color: #2563eb;
        border-color: #2563eb;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">I</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Infoma</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-700 hover:text-blue-600 transition duration-300 {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('residences.index') }}"
                        class="text-gray-700 hover:text-blue-600 transition duration-300 {{ request()->routeIs('residences.*') ? 'text-blue-600 font-semibold' : '' }}">
                        Tempat Tinggal
                    </a>
                    <a href="{{ route('activities.index') }}"
                        class="text-gray-700 hover:text-blue-600 transition duration-300 {{ request()->routeIs('activities.*') ? 'text-blue-600 font-semibold' : '' }}">
                        Kegiatan Kampus
                    </a>
                    <a href="{{ route('bookmarks.index') }}"
                        class="text-gray-700 hover:text-blue-600 transition duration-300 {{ request()->routeIs('bookmarks.*') ? 'text-blue-600 font-semibold' : '' }}">
                        Bookmark
                    </a>
                    <a href="{{ route('contact') }}"
                        class="text-gray-700 hover:text-blue-600 transition duration-300 {{ request()->routeIs('contact') ? 'text-blue-600 font-semibold' : '' }}">
                        Kontak
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative p-2 text-gray-400 hover:text-gray-600 transition duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @if(auth()->user()->notifications()->whereNull('read_at')->count() > 0)
                            <span
                                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ auth()->user()->notifications()->whereNull('read_at')->count() }}</span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50">
                            <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                <strong>Notifikasi</strong>
                            </div>
                            @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                            <a href="{{ route('notifications.show', $notification) }}" 
                               class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @php
                                            $status = $notification->data['status'] ?? null;
                                        @endphp
                                        @switch($status)
                                            @case('provider_approved')
                                                <div class="p-1 rounded-full bg-green-100">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                                @break
                                            @case('provider_rejected')
                                                <div class="p-1 rounded-full bg-red-100">
                                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                                @break
                                            @default
                                                <div class="p-1 rounded-full bg-blue-100">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                        @endswitch
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="font-medium text-gray-900">{{ $notification->data['message'] ?? 'Notifikasi baru' }}</p>
                                        <p class="text-gray-500 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                Tidak ada notifikasi
                            </div>
                            @endforelse
                            <div class="border-t border-gray-100">
                                <a href="{{ route('notifications.index') }}"
                                    class="block w-full text-center px-4 py-2 text-sm text-blue-600 hover:bg-gray-100">
                                    Lihat Semua Notifikasi
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition duration-300 focus:outline-none">
                            <div class="flex justify-center">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover"
                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff';">
                            </div>
                            <span class="hidden md:block font-medium">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z">
                                        </path>
                                    </svg>
                                    <span>Dashboard</span>
                                </div>
                            </a>
                            <a href="{{ route('profile.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    <span>Profil</span>
                                </div>
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <span>Keluar</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Daftar
                    </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button x-data="{ open: false }" @click="open = !open"
                            class="text-gray-700 hover:text-blue-600 transition duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden" x-data="{ open: false }" x-show="open" x-cloak>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('residences.index') }}"
                    class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('residences.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Tempat Tinggal
                </a>
                <a href="{{ route('activities.index') }}"
                    class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('activities.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Kegiatan Kampus
                </a>
                <a href="{{ route('bookmarks.index') }}"
                    class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('bookmarks.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Bookmark
                </a>
                <a href="{{ route('contact') }}"
                    class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('contact') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Kontak
                </a>
                @auth
                <div class="border-t border-gray-200 pt-4" x-data="{ submenuOpen: false }"
                    @mouseenter="submenuOpen = true" @mouseleave="submenuOpen = false"
                    @click.away="submenuOpen = false">
                    <button type="button"
                        class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 focus:outline-none"
                        @click="submenuOpen = !submenuOpen">
                        Menu Pengguna
                        <svg class="inline w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="submenuOpen" x-cloak class="mt-2 space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('profile.index') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('profile.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="bg-green-50 border border-green-200 rounded-md p-4" x-data="{ show: true }" x-show="show">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="show = false" class="text-green-400 hover:text-green-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="bg-red-50 border border-red-200 rounded-md p-4" x-data="{ show: true }" x-show="show">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="show = false" class="text-red-400 hover:text-red-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo and Description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">I</span>
                        </div>
                        <span class="text-xl font-bold">Infoma</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Platform terpercaya untuk membantu mahasiswa menemukan tempat tinggal dan kegiatan kampus yang
                        sesuai dengan kebutuhan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.153 3.994.435 1.841 2.175 2.169 2.175 2.169 2.585 0 4.567-2.721 4.567-6.655 0-3.479-2.501-5.91-6.064-5.91-4.13 0-6.556 3.094-6.556 6.296 0 1.246.479 2.58 1.077 3.304.118.14.135.265.1.408-.108.452-.35 1.429-.398 1.628-.061.257-.196.312-.452.188-1.687-.786-2.74-3.252-2.74-5.233 0-4.283 3.113-8.22 8.974-8.22 4.71 0 8.369 3.355 8.369 7.839 0 4.679-2.949 8.436-7.043 8.436-1.376 0-2.672-.715-3.113-1.568-.68 2.596-1.037 3.94-1.568 5.92C9.423 23.836 10.664 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Menu Utama</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}"
                                class="text-gray-300 hover:text-white transition duration-300">Beranda</a></li>
                        <li><a href="{{ route('residences.index') }}"
                                class="text-gray-300 hover:text-white transition duration-300">Tempat Tinggal</a></li>
                        <li><a href="{{ route('activities.index') }}"
                                class="text-gray-300 hover:text-white transition duration-300">Kegiatan Kampus</a></li>
                        <li><a href="{{ route('contact') }}"
                                class="text-gray-300 hover:text-white transition duration-300">Kontak</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Bantuan</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">FAQ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Panduan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Kebijakan
                                Privasi</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-300">Syarat &
                                Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} Infoma. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.add('hidden'));

                // Add active class to clicked button and show corresponding content
                button.classList.add('active');
                document.getElementById(targetTab + '-tab').classList.remove('hidden');
            });
        });
    });

    // Global functions for booking actions
    function viewBookingDetail(bookingId) {
        window.location.href = `/dashboard/bookings/residence/${bookingId}`;
    }

    function viewActivityBookingDetail(bookingId) {
        window.location.href = `/dashboard/bookings/activity/${bookingId}`;
    }

    function cancelBooking(bookingId) {
        if (confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
            // Show cancel form or redirect to cancel page
            window.location.href = `/dashboard/bookings/residence/${bookingId}/cancel`;
        }
    }

    function cancelActivityBooking(bookingId) {
        if (confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
            // Show cancel form or redirect to cancel page
            window.location.href = `/dashboard/bookings/activity/${bookingId}/cancel`;
        }
    }

    // CSRF token setup for AJAX requests
    window.axios = require('axios');
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error(
            'CSRF token tidak ditemukan. Pastikan ada meta tag <meta name="csrf-token" content="..."> di dalam <head>.'
        );
    }
    </script>
    @stack('scripts')
</body>