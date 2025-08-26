<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class VoucherService
{
    public function validateVoucher(string $code, $bookable, float $amount): array
    {
        $voucher = Voucher::where('code', $code)
            ->where('discountable_type', get_class($bookable))
            ->where('discountable_id', $bookable->id)
            ->first();

        if (!$voucher) {
            return [
                'valid' => false,
                'message' => 'Kode voucher tidak valid untuk item ini'
            ];
        }

        if (!$voucher->is_active) {
            return [
                'valid' => false,
                'message' => 'Voucher tidak aktif'
            ];
        }

        if (now() < $voucher->start_date || now() > $voucher->end_date) {
            return [
                'valid' => false,
                'message' => 'Voucher sudah tidak berlaku'
            ];
        }

        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Voucher sudah habis digunakan'
            ];
        }

        if ($voucher->min_purchase && $amount < $voucher->min_purchase) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($voucher->min_purchase, 0, ',', '.')
            ];
        }

        return [
            'valid' => true,
            'voucher' => $voucher,
            'discount_amount' => $this->calculateDiscount($voucher, $amount)
        ];
    }

    public function calculateDiscount(Voucher $voucher, float $amount): float
    {
        $discount = $voucher->discount_type === 'percentage' 
            ? ($amount * $voucher->discount_value / 100)
            : $voucher->discount_value;

        if ($voucher->max_discount) {
            $discount = min($discount, $voucher->max_discount);
        }

        return $discount;
    }

    public function useVoucher(Voucher $voucher, Booking $booking): void
    {
        DB::transaction(function () use ($voucher, $booking) {
            $voucher->increment('used_count');
            
            $booking->update([
                'discount_amount' => $booking->discount_amount,
                'final_amount' => $booking->total_amount - $booking->discount_amount
            ]);
        });
    }
}