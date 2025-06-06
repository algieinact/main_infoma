@extends('layouts.appAdmin')

@section('title', 'Edit Diskon')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Diskon</h1>
                    <p class="mt-2 text-gray-600">Edit diskon untuk tempat tinggal atau kegiatan Anda.</p>
                </div>
                <a href="{{ route('provider.discounts.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form action="{{ route('provider.discounts.update', $discount) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Diskon</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $discount->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700">Kode Diskon</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $discount->code) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">{{ old('description', $discount->description) }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Discount Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Detail Diskon</h3>
                        
                        <div>
                            <label for="discountable_type" class="block text-sm font-medium text-gray-700">Tipe Item</label>
                            <select name="discountable_type" id="discountable_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Pilih Tipe Item</option>
                                <option value="App\Models\Residence" {{ old('discountable_type', $discount->discountable_type) == 'App\Models\Residence' ? 'selected' : '' }}>
                                    Tempat Tinggal
                                </option>
                                <option value="App\Models\Activity" {{ old('discountable_type', $discount->discountable_type) == 'App\Models\Activity' ? 'selected' : '' }}>
                                    Kegiatan
                                </option>
                            </select>
                            @error('discountable_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="discountable_id" class="block text-sm font-medium text-gray-700">Item</label>
                            <select name="discountable_id" id="discountable_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Pilih Item</option>
                            </select>
                            @error('discountable_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah Diskon</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $discount->amount) }}" step="0.01"
                                    class="block w-full rounded-none rounded-l-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    <span id="amount_type">{{ $discount->is_percentage ? '%' : 'Rp' }}</span>
                                </span>
                            </div>
                            @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Diskon</label>
                            <div class="mt-2 space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                                <div class="flex items-center">
                                    <input type="radio" name="is_percentage" value="0" {{ old('is_percentage', $discount->is_percentage) == '0' ? 'checked' : '' }}
                                        class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300">
                                    <label class="ml-3 block text-sm font-medium text-gray-700">
                                        Nominal
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="is_percentage" value="1" {{ old('is_percentage', $discount->is_percentage) == '1' ? 'checked' : '' }}
                                        class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300">
                                    <label class="ml-3 block text-sm font-medium text-gray-700">
                                        Persentase
                                    </label>
                                </div>
                            </div>
                            @error('is_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Pengaturan Tambahan</h3>
                        
                        <div>
                            <label for="min_amount" class="block text-sm font-medium text-gray-700">Minimum Pembelian</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    Rp
                                </span>
                                <input type="number" name="min_amount" id="min_amount" value="{{ old('min_amount', $discount->min_amount) }}"
                                    class="block w-full rounded-none rounded-r-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            </div>
                            @error('min_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_discount" class="block text-sm font-medium text-gray-700">Maksimum Diskon</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    Rp
                                </span>
                                <input type="number" name="max_discount" id="max_discount" value="{{ old('max_discount', $discount->max_discount) }}"
                                    class="block w-full rounded-none rounded-r-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            </div>
                            @error('max_discount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700">Batas Penggunaan</label>
                            <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $discount->usage_limit) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('usage_limit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Validity Period -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Periode Berlaku</h3>
                        
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $discount->start_date->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $discount->end_date->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-600">Aktif</span>
                                </label>
                            </div>
                            @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountableType = document.getElementById('discountable_type');
        const discountableId = document.getElementById('discountable_id');
        const isPercentage = document.querySelectorAll('input[name="is_percentage"]');
        const amountType = document.getElementById('amount_type');

        // Handle discountable type change
        discountableType.addEventListener('change', function() {
            const type = this.value;
            discountableId.innerHTML = '<option value="">Pilih Item</option>';

            if (type) {
                fetch(`/provider/discounts/get-items?type=${type}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.title;
                            if (item.id == '{{ old('discountable_id', $discount->discountable_id) }}') {
                                option.selected = true;
                            }
                            discountableId.appendChild(option);
                        });
                    });
            }
        });

        // Handle discount type change
        isPercentage.forEach(radio => {
            radio.addEventListener('change', function() {
                amountType.textContent = this.value === '1' ? '%' : 'Rp';
            });
        });

        // Trigger initial load
        if (discountableType.value) {
            discountableType.dispatchEvent(new Event('change'));
        }
        if (document.querySelector('input[name="is_percentage"]:checked')) {
            document.querySelector('input[name="is_percentage"]:checked').dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection 