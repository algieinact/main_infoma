@props(['notifications'])

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($notifications->where('read_at', null)->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $notifications->where('read_at', null)->count() }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
        </div>
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @switch($notification->data['status'] ?? '')
                                @case('provider_approved')
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @break
                                @case('provider_rejected')
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                            @endswitch
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-900">
                                {{ $notification->data['message'] }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                            @if(isset($notification->data['booking_code']))
                                <a href="{{ route('bookings.show', $notification->data['booking_id']) }}" 
                                   class="mt-2 inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                                    Lihat Detail
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>
        @if($notifications->count() > 0)
            <div class="p-4 border-t border-gray-200">
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-sm text-blue-600 hover:text-blue-900">
                        Tandai semua sebagai telah dibaca
                    </button>
                </form>
            </div>
        @endif
    </div>
</div> 