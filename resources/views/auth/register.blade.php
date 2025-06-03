<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Infoma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#3B82F6',
                    secondary: '#1E40AF'
                }
            }
        }
    }
    </script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-white">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div
                    class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Bergabung dengan Infoma</h2>
                <p class="text-gray-600">Buat akun baru untuk mengakses layanan kami</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                <form class="space-y-6" action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-user-tag mr-2 text-blue-500"></i>Daftar sebagai
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer hover:bg-blue-50 transition duration-200">
                                <input type="radio" name="role" value="user" class="sr-only" checked>
                                <div class="text-center">
                                    <i class="fas fa-user text-blue-500 text-lg mb-2"></i>
                                    <p class="text-sm font-medium">Mahasiswa</p>
                                    <p class="text-xs text-gray-500">Cari tempat tinggal & kegiatan</p>
                                </div>
                            </label>
                            <label
                                class="flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer hover:bg-blue-50 transition duration-200">
                                <input type="radio" name="role" value="provider" class="sr-only">
                                <div class="text-center">
                                    <i class="fas fa-store text-blue-500 text-lg mb-2"></i>
                                    <p class="text-sm font-medium">Penyedia</p>
                                    <p class="text-xs text-gray-500">Sediakan tempat & kegiatan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nama Lengkap
                        </label>
                        <input id="name" name="name" type="text" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white"
                            placeholder="Nama lengkap">
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Email
                        </label>
                        <input id="email" name="email" type="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white"
                            placeholder="nama@email.com">
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-blue-500"></i>Nomor Telepon
                        </label>
                        <input id="phone" name="phone" type="tel" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- University Field -->
                    <div id="university_field">
                        <label for="university" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>Universitas
                        </label>
                        <input id="university" name="university" type="text" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white"
                            placeholder="Nama universitas">
                    </div>

                    <!-- Major Field -->
                    <div>
                        <label for="major" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book mr-2 text-blue-500"></i>Jurusan
                        </label>
                        <input id="major" name="major" type="text" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white"
                            placeholder="Nama jurusan">
                    </div>

                    <!-- Graduation Year Field -->
                    <div>
                        <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Tahun Lulus
                        </label>
                        <input id="graduation_year" name="graduation_year" type="number" min="2020" max="2030" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white"
                            placeholder="Tahun lulus">
                    </div>

                    <!-- Address Field -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>Alamat
                        </label>
                        <input id="address" name="address" type="text" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white"
                            placeholder="Alamat lengkap">
                    </div>

                    <!-- Birth Date Field -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-birthday-cake mr-2 text-blue-500"></i>Tanggal Lahir
                        </label>
                        <input id="birth_date" name="birth_date" type="date" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white">
                    </div>

                    <!-- Gender Field -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-2 text-blue-500"></i>Jenis Kelamin
                        </label>
                        <select id="gender" name="gender" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white pr-12"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="toggleIcon1" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white pr-12"
                                    placeholder="Ulangi password">
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="toggleIcon2" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Privacy -->
                    <div class="flex items-start">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                        <label for="terms" class="ml-3 block text-sm text-gray-700">
                            Saya menyetujui <a href="#" class="text-blue-600 hover:text-blue-500">Syarat & Ketentuan</a>
                            dan <a href="#" class="text-blue-600 hover:text-blue-500">Kebijakan Privasi</a> Infoma
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-user-plus text-blue-200 group-hover:text-blue-100"></i>
                            </span>
                            Daftar Sekarang
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">atau</span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}"
                                class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <p>&copy; 2024 Infoma. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const iconNumber = fieldId === 'password' ? '1' : '2';
        const toggleIcon = document.getElementById('toggleIcon' + iconNumber);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Role selection functionality
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove selected class from all role labels
            document.querySelectorAll('label').forEach(label => {
                if (label.querySelector('input[name="role"]')) {
                    label.classList.remove('bg-blue-100', 'border-blue-500');
                    label.classList.add('border-gray-300');
                }
            });

            // Add selected class to current label
            if (this.checked) {
                this.closest('label').classList.add('bg-blue-100', 'border-blue-500');
                this.closest('label').classList.remove('border-gray-300');
            }

            // Show/hide fields based on role
            const universityField = document.getElementById('university_field');
            const studentIdField = document.getElementById('student_id_field');
            const businessNameField = document.getElementById('business_name_field');

            if (this.value === 'user') {
                universityField.classList.remove('hidden');
                studentIdField.classList.remove('hidden');
                businessNameField.classList.add('hidden');
                document.getElementById('university').required = true;
                document.getElementById('student_id').required = true;
                document.getElementById('business_name').required = false;
            } else if (this.value === 'provider') {
                universityField.classList.add('hidden');
                studentIdField.classList.add('hidden');
                businessNameField.classList.remove('hidden');
                document.getElementById('university').required = false;
                document.getElementById('student_id').required = false;
                document.getElementById('business_name').required = true;
            }
        });
    });

    // Set initial selection
    document.querySelector('input[name="role"]:checked').closest('label').classList.add('bg-blue-100',
        'border-blue-500');
    document.querySelector('input[name="role"]:checked').closest('label').classList.remove('border-gray-300');

    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;

        if (confirmPassword && password !== confirmPassword) {
            this.setCustomValidity('Password tidak cocok');
            this.classList.add('border-red-500');
            this.classList.remove('border-gray-300');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-300');
        }
    });
    </script>
</body>

</html>