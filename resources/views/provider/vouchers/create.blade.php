@extends('layouts.appAdmin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Buat Voucher Baru</h1>
            <a href="{{ route('provider.vouchers.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('provider.vouchers.store') }}" method="POST">
                @csrf

                <!-- Item Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Pilih Item
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Tipe Item</label>
                            <select name="discountable_type" id="discountable_type" 
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="App\Models\Activity">Activity</option>
                                <option value="App\Models\Residence">Residence</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Item</label>
                            <select name="discountable_id" id="discountable_id" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Item</option>
                                @foreach($activities as $activity)
                                    <option value="{{ $activity->id }}" data-type="App\Models\Activity">
                                        {{ $activity->title }}
                                    </option>
                                @endforeach
                                @foreach($residences as $residence)
                                    <option value="{{ $residence->id }}" data-type="App\Models\Residence">
                                        {{ $residence->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Discount Details -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Detail Diskon
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Tipe Diskon</label>
                            <select name="discount_type" id="discount_type" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal Tetap</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Nilai Diskon</label>
                            <div class="relative">
                                <input type="number" name="discount_value" required
                                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       min="0" step="0.01">
                                <span class="absolute right-3 top-2 text-gray-500" id="discount_suffix">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Pengaturan Tambahan
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Minimal Pembelian</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" name="min_purchase"
                                       class="w-full pl-8 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       min="0" step="1000">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Maksimal Diskon</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" name="max_discount"
                                       class="w-full pl-8 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       min="0" step="1000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validity Period -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Periode Berlaku
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Tanggal Berakhir</label>
                            <input type="date" name="end_date" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                    </div>
                </div>

                <!-- Usage Limit -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Batas Penggunaan
                    </label>
                    <div class="flex items-center">
                        <input type="number" name="usage_limit"
                               class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               min="1" step="1" placeholder="Kosongkan untuk tidak terbatas">
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Deskripsi voucher (opsional)"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        Buat Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountType = document.getElementById('discount_type');
    const discountSuffix = document.getElementById('discount_suffix');
    const discountableType = document.getElementById('discountable_type');
    const discountableId = document.getElementById('discountable_id');

    // Update discount suffix based on type
    discountType.addEventListener('change', function() {
        discountSuffix.textContent = this.value === 'percentage' ? '%' : 'Rp';
    });

    // Filter items based on selected type
    discountableType.addEventListener('change', function() {
        const selectedType = this.value;
        const options = discountableId.options;

        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            if (option.value === '') continue;

            if (option.dataset.type === selectedType) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }

        // Reset selection
        discountableId.value = '';
    });
});
</script>
@endpush
@endsection 