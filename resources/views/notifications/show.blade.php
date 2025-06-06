@extends('layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('notifications.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Notifikasi
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @switch($notification->data['status'] ?? '')
                            @case('provider_approved')
                                <div class="p-3 rounded-full bg-green-100">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                @break
                            @case('provider_rejected')
                                <div class="p-3 rounded-full bg-red-100">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                @break
                            @default
                                <div class="p-3 rounded-full bg-blue-100">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                        @endswitch
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <h1 class="text-2xl font-bold text-gray-900">Detail Notifikasi</h1>
                            <p class="text-sm text-gray-500">
                                {{ $notification->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        
                        <div class="mt-6 space-y-6">
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Pesan</h2>
                                <p class="mt-1 text-lg text-gray-900">{{ $notification->data['message'] }}</p>
                            </div>

                            @if(isset($notification->data['booking_code']))
                                <div>
                                    <h2 class="text-sm font-medium text-gray-500">Kode Booking</h2>
                                    <p class="mt-1 text-lg text-gray-900">{{ $notification->data['booking_code'] }}</p>
                                </div>
                            @endif

                            @if(isset($notification->data['reason']))
                                <div>
                                    <h2 class="text-sm font-medium text-gray-500">Alasan</h2>
                                    <p class="mt-1 text-lg text-gray-900">{{ $notification->data['reason'] }}</p>
                                </div>
                            @endif

                            @if(isset($notification->data['booking_id']))
                                <div class="pt-6">
                                    <a href="{{ route('bookings.show', $notification->data['booking_id']) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Lihat Detail Booking
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 