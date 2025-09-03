@extends('layouts.appAdmin')

@section('title', 'Buat Voucher Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Buat Voucher Baru</h1>
        <p class="mt-2 text-gray-600">Buat voucher untuk menarik lebih banyak customer ke residence atau activity Anda</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('provider.vouchers.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Item Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Item</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Item</label>
                        <select name="discountable_type" id="discountable_type" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Tipe</option>
                            <option value="App\Models\Residence" {{ old('discountable_type') == 'App\Models\Residence' ? 'selected' : '' }}>Residence</option>
                            <option value="App\Models\Activity" {{ old('discountable_type') == 'App\Models\Activity' ? 'selected' : '' }}>Activity</option>
                        </select>
                        @error('discountable_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                        <select name="discountable_id" id="discountable_id" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Item</option>
                        </select>
                        @error('discountable_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Discount Configuration -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Diskon</label>
                    <select name="discount_type" id="discount_type" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Tipe</option>
                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                    @error('discount_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Diskon</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500" id="discount_prefix">
                            %
                        </span>
                        <input type="number" name="discount_value" id="discount_value" 
                               class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="0" min="0" step="0.01" required>
                    </div>
                    @error('discount_value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Diskon (Opsional)</label>
                    <input type="number" name="max_discount" id="max_discount" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
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
                           value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" id="end_date" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           value="{{ old('end_date') }}" required>
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
                          placeholder="Deskripsi voucher untuk customer...">{{ old('description') }}</textarea>
                @error('description')
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
                    Buat Voucher
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
    const discountableTypeSelect = document.getElementById('discountable_type');
    const discountableIdSelect = document.getElementById('discountable_id');
    
    // Store the original data
    const residences = @json($residences);
    const activities = @json($activities);
    
    // Handle discount type change
    discountTypeSelect.addEventListener('change', function() {
        if (this.value === 'percentage') {
            discountPrefix.textContent = '%';
            discountValueInput.placeholder = '0';
            discountValueInput.step = '0.01';
            discountValueInput.max = '100';
            maxDiscountInput.parentElement.style.display = 'block';
        } else {
            discountPrefix.textContent = 'Rp';
            discountValueInput.placeholder = '0';
            discountValueInput.step = '1000';
            discountValueInput.max = '';
            maxDiscountInput.parentElement.style.display = 'none';
        }
    });
    
    // Validate discount value on input
    discountValueInput.addEventListener('input', function() {
        if (discountTypeSelect.value === 'percentage' && this.value > 100) {
            this.setCustomValidity('Diskon persentase tidak boleh lebih dari 100%');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Handle discountable type change
    discountableTypeSelect.addEventListener('change', function() {
        discountableIdSelect.innerHTML = '<option value="">Pilih Item</option>';
        
        if (this.value === 'App\\Models\\Residence') {
            residences.forEach(function(residence) {
                const option = document.createElement('option');
                option.value = residence.id;
                option.textContent = residence.title;
                discountableIdSelect.appendChild(option);
            });
        } else if (this.value === 'App\\Models\\Activity') {
            activities.forEach(function(activity) {
                const option = document.createElement('option');
                option.value = activity.id;
                option.textContent = activity.title;
                discountableIdSelect.appendChild(option);
            });
        }
    });
    
    // Set minimum start date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').min = today;
    
    // Handle start date change
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        const endDateInput = document.getElementById('end_date');
        
        if (startDate) {
            endDateInput.min = startDate;
            // If end date is before start date, clear it
            if (endDateInput.value && endDateInput.value <= startDate) {
                endDateInput.value = '';
            }
        }
    });
    
    // Initialize end date minimum when page loads
    const startDateValue = document.getElementById('start_date').value;
    if (startDateValue) {
        document.getElementById('end_date').min = startDateValue;
    }
});
</script>
@endsection 