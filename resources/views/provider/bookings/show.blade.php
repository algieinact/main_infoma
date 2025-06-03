@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Booking</h1>
                    <p class="mt-2 text-gray-600">Kode Booking: {{ $booking->booking_code }}</p>
                </div>
                <a href="{{ route('provider.dashboard') }}" class="text-blue-600 hover:text-blue-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Booking Details -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Booking</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    @switch($booking->status)
                                        @case('waiting_provider_approval')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Menunggu Persetujuan
                                            </span>
                                            @break
                                        @case('provider_approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                            @break
                                        @case('provider_rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                    @endswitch
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Booking</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Item</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->bookable->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->bookable->category->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->start_date->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->end_date->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->duration }} hari</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Harga</dt>
                                <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($booking->total_amount) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Pemesan</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->phone ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($booking->additional_info)
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Tambahan</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-900">
                            {{ $booking->additional_info }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Panel -->
            <div class="md:col-span-1">
                @if($booking->status === 'waiting_provider_approval')
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Persetujuan Booking</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('provider.bookings.approve', $booking) }}" method="POST" class="mb-4">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Setujui Booking
                            </button>
                        </form>

                        <form action="{{ route('provider.bookings.reject', $booking) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                Tolak Booking
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Transaction Information -->
                @if($booking->transaction)
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Pembayaran</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status Pembayaran</dt>
                                <dd class="mt-1">
                                    @switch($booking->transaction->status)
                                        @case('pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Menunggu Pembayaran
                                            </span>
                                            @break
                                        @case('paid')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Lunas
                                            </span>
                                            @break
                                        @case('failed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Gagal
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($booking->transaction->status) }}
                                            </span>
                                    @endswitch
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Metode Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->transaction->payment_method }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($booking->transaction->amount) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 