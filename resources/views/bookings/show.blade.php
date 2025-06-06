@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold mb-1">Booking #{{ $booking->booking_code }}</h2>
            <p class="text-gray-500 text-sm">Dibuat pada {{ $booking->booking_date->format('d M Y H:i') }}</p>
        </div>
        <span class="inline-block mt-4 md:mt-0 px-4 py-2 rounded-full text-sm font-semibold
            @if($booking->status === 'confirmed') bg-green-100 text-green-800
            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800 @endif">
            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left/Main Column -->
        <div class="md:col-span-2 space-y-6">
            <!-- Item Details Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Detail Item</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="font-bold text-lg mb-2">{{ $booking->bookable->title }}</div>
                        <div class="text-gray-600 mb-2 flex items-center">
                            <i class="fas fa-building mr-2"></i>
                            {{ class_basename($booking->bookable_type) }}
                        </div>
                        <div class="text-gray-600 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $booking->bookable->location }}
                        </div>
                        <div class="text-gray-600 mb-2 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Provider: {{ $booking->bookable->provider->name }}
                        </div>
                        @if($booking->bookable_type === 'App\\Models\\Activity')
                        <div class="text-gray-600 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Kapasitas:
                            {{ $booking->bookable->current_participants }}/{{ $booking->bookable->max_participants }}
                        </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 rounded p-4">
                        <div class="font-semibold mb-2">Jadwal</div>
                        <div class="text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Mulai: {{ $booking->start_date->format('d M Y') }}
                        </div>
                        @if($booking->end_date)
                        <div class="text-gray-700 flex items-center">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Selesai: {{ $booking->end_date->format('d M Y') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Data Pemesan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-2"><span class="font-semibold">Nama:</span>
                            {{ $booking->booking_data['full_name'] ?? '-' }}</div>
                        <div class="mb-2"><span class="font-semibold">Telepon:</span>
                            {{ $booking->booking_data['phone'] ?? '-' }}</div>
                        @if(isset($booking->booking_data['emergency_contact']))
                        <div class="mb-2"><span class="font-semibold">Kontak Darurat:</span>
                            {{ $booking->booking_data['emergency_contact'] }}</div>
                        @endif
                    </div>
                    <div>
                        @if(isset($booking->booking_data['university']))
                        <div class="mb-2"><span class="font-semibold">Universitas:</span>
                            {{ $booking->booking_data['university'] }}</div>
                        @endif
                        @if(isset($booking->booking_data['major']))
                        <div class="mb-2"><span class="font-semibold">Jurusan:</span>
                            {{ $booking->booking_data['major'] }}</div>
                        @endif
                        @if(isset($booking->booking_data['student_id']))
                        <div class="mb-2"><span class="font-semibold">NIM:</span>
                            {{ $booking->booking_data['student_id'] }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            @if($booking->notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Catatan</h3>
                <p class="text-gray-700">{{ $booking->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Right/Sidebar Column -->
        <div class="space-y-6">
            <!-- Payment Details Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Detail Pembayaran</h3>
                <div class="flex justify-between mb-2">
                    <span>Total Biaya</span>
                    <span class="font-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($booking->discount_amount > 0)
                <div class="flex justify-between mb-2 text-green-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <hr class="my-2">
                <div class="flex justify-between">
                    <span class="font-bold">Biaya Akhir</span>
                    <span class="font-bold">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Documents Card -->
            @if($booking->files)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Dokumen</h3>
                <div class="space-y-2">
                    @foreach($booking->files as $key => $file)
                    <a href="{{ route('bookings.download-file', ['booking' => $booking->id, 'fileType' => $key]) }}"
                        class="block w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition">
                        <i class="fas fa-download mr-2"></i>
                        Download {{ ucfirst($key) }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <a href="{{ route('bookings.history') }}"
                    class="block w-full text-center mb-2 px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 font-semibold transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                <button type="button" onclick="document.getElementById('cancelModal').classList.remove('hidden')"
                    class="block w-full text-center px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 font-semibold transition">
                    <i class="fas fa-times mr-2"></i>Batalkan Booking
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal (Tailwind) -->
@if($booking->status === 'pending' || $booking->status === 'confirmed')
<div id="cancelModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto">
        <form action="{{ route('bookings.cancel', $booking) }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Batalkan Booking</h3>
            </div>
            <div class="px-6 py-4">
                <label for="cancellation_reason" class="block text-sm font-medium mb-2">Alasan Pembatalan</label>
                <textarea id="cancellation_reason" name="cancellation_reason" rows="3" required
                    class="w-full border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="px-6 py-4 flex justify-end space-x-2 border-t">
                <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')"
                    class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Tutup</button>
                <button type="submit"
                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 font-semibold">Batalkan
                    Booking</button>
            </div>
        </form>
    </div>
</div>
@endif

@push('styles')
<style>
/* Custom scrollbar for modal */
#cancelModal textarea {
    resize: vertical;
}
</style>
@endpush

@push('scripts')
<script>
// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('cancelModal');
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    }
});
</script>
@endpush
@endsection