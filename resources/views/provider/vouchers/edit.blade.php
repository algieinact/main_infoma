@extends('layouts.appAdmin')

@section('title', 'Edit Voucher')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Voucher</h1>
        <p class="mt-2 text-gray-600">Edit voucher "{{ $voucher->code }}"</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('provider.vouchers.update', $voucher) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Item Selection (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Item</label>
                        <input type="text" value="{{ $voucher->discountable_type === 'App\\Models\\Residence' ? 'Residence' : 'Activity' }}" 
                               class="w-full border-gray-300 rounded-md bg-gray-50" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                        <input type="text" value="{{ $voucher->discountable->title }}" 
                               class="w-full border-gray-300 rounded-md bg-gray-50" readonly>
                    </div>
                </div>
            </div>

            <!-- Discount Configuration -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Diskon</label>
                    <select name="discount_type" id="discount_type" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="percentage" {{ old('discount_type', $voucher->discount_type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('discount_type', $voucher->discount_type) == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                    @error('discount_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Diskon</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500" id="discount_prefix">
                            {{ $voucher->discount_type === 'percentage' ? '%' : 'Rp' }}
                        </span>
                        <input type="number" name="discount_value" id="discount_value" 
                               class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               value="{{ old('discount_value', $voucher->discount_value) }}" 
                               placeholder="0" min="0" step="{{ $voucher->discount_type === 'percentage' ? '0.01' : '1000' }}" required>
                    </div>
                    @error('discount_value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Diskon (Opsional)</label>
                    <input type="number" name="max_discount" id="max_discount" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           value="{{ old('max_discount', $voucher->max_discount) }}"
                           placeholder="0" min="0" step="1000">
                    <p class="text-xs text-gray-500 mt-1">Hanya untuk diskon persentase</p>
                    @error('max_discount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Conditions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimal Pembelian (Opsional)</label>
                    <input type="number" name="min_purchase" id="min_purchase" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           value="{{ old('min_purchase', $voucher->min_purchase) }}"
                           placeholder="0" min="0" step="1000">
                    <p class="text-xs text-gray-500 mt-1">Minimal total pembelian untuk menggunakan voucher</p>
                    @error('min_purchase')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Batas Penggunaan (Opsional)</label>
                    <input type="number" name="usage_limit" id="usage_limit" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           value="{{ old('usage_limit', $voucher->usage_limit) }}"
                           placeholder="0" min="1" step="1">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan untuk unlimited</p>
                    @error('usage_limit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           value="{{ old('start_date', $voucher->start_date->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" id="end_date" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           value="{{ old('end_date', $voucher->end_date->format('Y-m-d')) }}" required>
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3" 
                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                          placeholder="Deskripsi voucher untuk customer...">{{ old('description', $voucher->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Voucher aktif</span>
                </label>
                @error('is_active')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('provider.vouchers.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountTypeSelect = document.getElementById('discount_type');
    const discountValueInput = document.getElementById('discount_value');
    const maxDiscountInput = document.getElementById('max_discount');
    const discountPrefix = document.getElementById('discount_prefix');
    
    // Handle discount type change
    discountTypeSelect.addEventListener('change', function() {
        if (this.value === 'percentage') {
            discountPrefix.textContent = '%';
            discountValueInput.placeholder = '0';
            discountValueInput.step = '0.01';
            maxDiscountInput.parentElement.style.display = 'block';
        } else {
            discountPrefix.textContent = 'Rp';
            discountValueInput.placeholder = '0';
            discountValueInput.step = '1000';
            maxDiscountInput.parentElement.style.display = 'none';
        }
    });
    
    // Handle start date change
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
    });
});
</script>
@endsection
