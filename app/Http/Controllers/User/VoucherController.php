<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Activity;
use App\Models\Residence;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VoucherController extends Controller
{
    public function validateVoucher(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:20',
            'bookable_type' => 'required|in:App\Models\Activity,App\Models\Residence',
            'bookable_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->code));
        $bookableType = $request->bookable_type;
        $bookableId = $request->bookable_id;
        $amount = $request->amount;

        // Find the voucher
        $voucher = Voucher::where('code', $code)
            ->where('discountable_type', $bookableType)
            ->where('discountable_id', $bookableId)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan atau tidak berlaku untuk item ini.'
            ], 404);
        }

        // Check if voucher is valid
        if (!$voucher->isValid()) {
            $message = 'Voucher tidak valid. ';
            if (!$voucher->is_active) {
                $message .= 'Voucher tidak aktif.';
            } elseif (now() < $voucher->start_date) {
                $message .= 'Voucher belum berlaku.';
            } elseif (now() > $voucher->end_date) {
                $message .= 'Voucher sudah expired.';
            } elseif ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
                $message .= 'Voucher sudah habis digunakan.';
            }
            
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        // Check minimum purchase
        if ($voucher->min_purchase && $amount < $voucher->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => "Minimal pembelian Rp " . number_format($voucher->min_purchase, 0, ',', '.') . " untuk menggunakan voucher ini."
            ], 400);
        }

        // Calculate discount
        $discount = $voucher->calculateDiscount($amount);
        $finalAmount = $amount - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil digunakan!',
            'data' => [
                'voucher_id' => $voucher->id,
                'code' => $voucher->code,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'discount_amount' => $discount,
                'original_amount' => $amount,
                'final_amount' => $finalAmount,
                'description' => $voucher->description,
            ]
        ]);
    }
}