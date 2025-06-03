<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infoma - Informasi Kebutuhan Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#3B82F6',
                    secondary: '#1E40AF',
                }
            }
        }
    }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="text-2xl font-bold text-primary">
                        <i class="fas fa-home mr-2"></i>Infoma
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#home"
                            class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Beranda</a>
                        <a href="#features"
                            class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Fitur</a>
                        <a href="#how-it-works"
                            class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Cara
                            Kerja</a>
                        <a href="#contact"
                            class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Kontak</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-primary hover:text-secondary font-medium">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg font-medium transition-colors">Daftar</a>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-primary">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#home"
                    class="block text-gray-700 hover:text-primary px-3 py-2 rounded-md text-base font-medium">Beranda</a>
                <a href="#features"
                    class="block text-gray-700 hover:text-primary px-3 py-2 rounded-md text-base font-medium">Fitur</a>
                <a href="#how-it-works"
                    class="block text-gray-700 hover:text-primary px-3 py-2 rounded-md text-base font-medium">Cara
                    Kerja</a>
                <a href="#contact"
                    class="block text-gray-700 hover:text-primary px-3 py-2 rounded-md text-base font-medium">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="bg-gradient-to-br from-blue-50 to-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Temukan <span class="text-primary">Residence</span> dan
                        <span class="text-primary">Kegiatan Kampus</span> Terbaik
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Platform lengkap untuk mahasiswa dalam mencari kost, kontrakan, serta informasi kegiatan kampus
                        seperti seminar, webinar, dan lomba.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="bg-primary hover:bg-secondary text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                        </a>
                        <a href="#features"
                            class="border-2 border-primary text-primary hover:bg-primary hover:text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300">
                            <i class="fas fa-play mr-2"></i>Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="text-center">
                    <div class="relative">
                        <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-8 shadow-2xl">
                            <div class="grid grid-cols-2 gap-4 text-white">
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                                    <i class="fas fa-home text-3xl mb-2"></i>
                                    <h3 class="font-bold">1000+</h3>
                                    <p class="text-sm">Residence</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                                    <i class="fas fa-calendar text-3xl mb-2"></i>
                                    <h3 class="font-bold">500+</h3>
                                    <p class="text-sm">Kegiatan</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                                    <i class="fas fa-users text-3xl mb-2"></i>
                                    <h3 class="font-bold">5000+</h3>
                                    <p class="text-sm">Mahasiswa</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                                    <i class="fas fa-star text-3xl mb-2"></i>
                                    <h3 class="font-bold">4.8</h3>
                                    <p class="text-sm">Rating</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Infoma menyediakan berbagai fitur lengkap untuk
                    memenuhi kebutuhan mahasiswa</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-home text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Residence</h3>
                    <p class="text-gray-600 mb-4">Cari kost dan kontrakan dengan mudah. Booking online dengan sistem
                        yang aman dan terpercaya.</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Upload berkas digital</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Transaksi dalam aplikasi</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Sistem pembatalan fleksibel</li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-alt text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Kegiatan Kampus</h3>
                    <p class="text-gray-600 mb-4">Temukan seminar, webinar, mentoring, lomba, dan kegiatan kampus
                        lainnya.</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Daftar kegiatan mudah</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Notifikasi real-time</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Sertifikat digital</li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-credit-card text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pembayaran Aman</h3>
                    <p class="text-gray-600 mb-4">Sistem pembayaran terintegrasi dengan berbagai metode pembayaran.</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Multiple payment gateway</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Transaksi terenkripsi</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Riwayat pembayaran</li>
                    </ul>
                </div>

                <!-- Feature 4 -->
                <div
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-bookmark text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Bookmark</h3>
                    <p class="text-gray-600 mb-4">Simpan Residence dan kegiatan favorit untuk akses cepat.</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Akses offline</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Kategori custom</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Sinkronisasi cloud</li>
                    </ul>
                </div>

                <!-- Feature 5 -->
                <div
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-percent text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Diskon & Promo</h3>
                    <p class="text-gray-600 mb-4">Dapatkan berbagai diskon menarik dari penyedia layanan.</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Diskon early bird</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Cashback program</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Member exclusive</li>
                    </ul>
                </div>

                <!-- Feature 6 -->
                <div
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-headset text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Customer Support</h3>
                    <p class="text-gray-600 mb-4">Tim support siap membantu 24/7 untuk menyelesaikan masalah Anda.</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Live chat</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>FAQ lengkap</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Video tutorial</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cara Kerja</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Mudah digunakan dalam 4 langkah sederhana</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div
                        class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                        1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Daftar Akun</h3>
                    <p class="text-gray-600">Buat akun gratis dan lengkapi profil Anda untuk pengalaman yang lebih
                        personal.</p>
                </div>
                <div class="text-center">
                    <div
                        class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                        2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Cari & Pilih</h3>
                    <p class="text-gray-600">Jelajahi berbagai pilihan Residence dan kegiatan sesuai kebutuhan
                        Anda.</p>
                </div>
                <div class="text-center">
                    <div
                        class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                        3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Booking & Bayar</h3>
                    <p class="text-gray-600">Lakukan booking dan pembayaran dengan aman melalui sistem terintegrasi.</p>
                </div>
                <div class="text-center">
                    <div
                        class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                        4</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Nikmati Layanan</h3>
                    <p class="text-gray-600">Nikmati Residence atau ikuti kegiatan sesuai dengan yang Anda pesan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-secondary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Memulai Pengalaman Baru?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">Bergabunglah dengan ribuan mahasiswa yang sudah
                merasakan kemudahan Infoma</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="bg-white text-primary hover:bg-gray-100 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Gratis
                </a>
                <a href="#"
                    class="border-2 border-white text-white hover:bg-white hover:text-primary px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300">
                    <i class="fas fa-phone mr-2"></i>Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="text-2xl font-bold text-primary mb-4">
                        <i class="fas fa-home mr-2"></i>Infoma
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">Platform terpercaya untuk mahasiswa dalam mencari tempat
                        tinggal dan kegiatan kampus. Memudahkan kehidupan mahasiswa dengan teknologi modern.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i
                                class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i
                                class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i
                                class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i
                                class="fab fa-linkedin text-xl"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-6">Layanan</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Residence</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Kegiatan Kampus</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Booking Online</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Customer Support</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-6">Kontak</h3>
                    <ul class="space-y-3">
                        <li class="text-gray-400"><i class="fas fa-envelope mr-2"></i>info@infoma.com</li>
                        <li class="text-gray-400"><i class="fas fa-phone mr-2"></i>+62 123 456 7890</li>
                        <li class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i>Jakarta, Indonesia</li>
                        <li class="text-gray-400"><i class="fas fa-clock mr-2"></i>24/7 Support</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; 2025 Infoma. All rights reserved. Made with ❤️ for Indonesian students.
                </p>
            </div>
        </div>
    </footer>

    <script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add scroll effect to navbar
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('nav');
        if (window.scrollY > 100) {
            navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
        } else {
            navbar.classList.remove('bg-white/95', 'backdrop-blur-sm');
        }
    });
    </script>
</body>

</html>