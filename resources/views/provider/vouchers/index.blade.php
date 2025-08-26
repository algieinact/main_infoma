@extends('layouts.appAdmin')

@section('title', 'Kelola Voucher')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Voucher</h1>
        <a href="{{ route('provider.vouchers.create') }}" 
           class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Buat Voucher Baru
        </a>
    </div>

    <!-- Vouchers List -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diskon
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Penggunaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $voucher->code }}</div>
                                @if($voucher->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($voucher->description, 30) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $voucher->discountable_type === 'App\\Models\\Residence' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $voucher->discountable_type === 'App\\Models\\Residence' ? 'Residence' : 'Activity' }}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-900">
                                        {{ optional($voucher->discountable)->title ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $voucher->formatted_discount }}</div>
                                @if($voucher->min_purchase)
                                    <div class="text-xs text-gray-500">Min: Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</div>
                                @endif
                                @if($voucher->max_discount && $voucher->discount_type === 'percentage')
                                    <div class="text-xs text-gray-500">Max: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $voucher->start_date->format('d/m/Y') }} - {{ $voucher->end_date->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $voucher->start_date->diffForHumans() }} - {{ $voucher->end_date->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'Active' => 'bg-green-100 text-green-800',
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Expired' => 'bg-red-100 text-red-800',
                                        'Used Up' => 'bg-gray-100 text-gray-800',
                                        'Inactive' => 'bg-gray-100 text-gray-600'
                                    ];
                                    $statusColor = $statusColors[$voucher->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $voucher->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($voucher->usage_limit)
                                        {{ $voucher->used_count }}/{{ $voucher->usage_limit }}
                                    @else
                                        {{ $voucher->used_count }} (Unlimited)
                                    @endif
                                </div>
                                @if($voucher->usage_limit)
                                    @php
                                        $percentage = ($voucher->used_count / $voucher->usage_limit) * 100;
                                    @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('provider.vouchers.edit', $voucher) }}" 
                                       class="text-blue-600 hover:text-blue-900">Edit</a>
                                    
                                    <form action="{{ route('provider.vouchers.toggle-status', $voucher) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-{{ $voucher->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $voucher->is_active ? 'yellow' : 'green' }}-900">
                                            {{ $voucher->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('provider.vouchers.destroy', $voucher) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')"
                                                class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada voucher</h3>
                                    <p class="text-gray-500">Mulai buat voucher untuk menarik lebih banyak customer!</p>
                                    <a href="{{ route('provider.vouchers.create') }}" 
                                       class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Buat Voucher Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($vouchers->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $vouchers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 