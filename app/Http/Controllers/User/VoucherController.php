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
    try {
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

        \Log::info('Voucher validation request - ENHANCED DEBUG', [
            'code' => $code,
            'bookable_type' => $bookableType,
            'bookable_id' => $bookableId,
            'amount' => $amount,
            'amount_type' => gettype($amount),
            'amount_formatted' => number_format($amount, 2)
        ]);

        // Find the voucher
        $voucher = Voucher::where('code', $code)
            ->where('discountable_type', $bookableType)
            ->where('discountable_id', $bookableId)
            ->first();

        \Log::info('Voucher search result', [
            'voucher_found' => $voucher ? true : false,
            'voucher_id' => $voucher ? $voucher->id : null,
            'voucher_active' => $voucher ? $voucher->is_active : null,
            'voucher_valid' => $voucher ? $voucher->isValid() : null,
            'voucher_discount_type' => $voucher ? $voucher->discount_type : null,
            'voucher_discount_value' => $voucher ? $voucher->discount_value : null
        ]);

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
            \Log::info('Voucher minimum purchase check failed', [
                'voucher_min_purchase' => $voucher->min_purchase,
                'provided_amount' => $amount
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Minimal pembelian Rp " . number_format($voucher->min_purchase, 0, ',', '.') . " untuk menggunakan voucher ini."
            ], 400);
        }

        // Check if user has already used this voucher
        $existingBooking = \App\Models\Booking::where('user_id', auth()->id())
            ->where('voucher_id', $voucher->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah menggunakan voucher ini sebelumnya.'
            ], 400);
        }

        // Calculate discount
        $discount = $voucher->calculateDiscount($amount);
        $finalAmount = $amount - $discount;

        \Log::info('Voucher calculation result - ENHANCED DEBUG', [
            'voucher_id' => $voucher->id,
            'voucher_code' => $voucher->code,
            'discount_type' => $voucher->discount_type,
            'discount_value' => $voucher->discount_value,
            'original_amount' => $amount,
            'original_amount_formatted' => number_format($amount, 2),
            'calculated_discount' => $discount,
            'calculated_discount_formatted' => number_format($discount, 2),
            'final_amount' => $finalAmount,
            'final_amount_formatted' => number_format($finalAmount, 2),
            'calculation_debug' => [
                'is_percentage' => $voucher->discount_type === 'percentage',
                'is_fixed' => $voucher->discount_type === 'fixed',
                'calculation_method' => $voucher->discount_type === 'percentage' 
                    ? "({$amount} * {$voucher->discount_value} / 100) = {$discount}"
                    : "min({$voucher->discount_value}, {$amount}) = {$discount}"
            ]
        ]);

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
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Voucher validation - Validation Exception', [
            'errors' => $e->errors()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Data tidak valid: ' . implode(', ', $e->errors()['code'] ?? $e->errors()['bookable_type'] ?? $e->errors()['bookable_id'] ?? $e->errors()['amount'] ?? ['Data tidak valid'])
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Voucher validation error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memvalidasi voucher.'
        ], 500);
    }
}

    public function getAvailableVouchers(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'bookable_type' => 'required|in:App\Models\Activity,App\Models\Residence',
                'bookable_id' => 'required|integer',
            ]);

            $bookableType = $request->bookable_type;
            $bookableId = $request->bookable_id;

            $vouchers = Voucher::where('discountable_type', $bookableType)
                ->where('discountable_id', $bookableId)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where(function ($query) {
                    $query->whereNull('usage_limit')
                        ->orWhereRaw('used_count < usage_limit');
                })
                ->get()
                ->filter(function ($voucher) {
                    // Check if user has already used this voucher
                    $existingBooking = \App\Models\Booking::where('user_id', auth()->id())
                        ->where('voucher_id', $voucher->id)
                        ->whereIn('status', ['confirmed', 'completed'])
                        ->exists();
                    
                    return !$existingBooking;
                });

            return response()->json([
                'success' => true,
                'data' => $vouchers->values()->map(function ($voucher) {
                    return [
                        'id' => $voucher->id,
                        'code' => $voucher->code,
                        'discount_type' => $voucher->discount_type,
                        'discount_value' => $voucher->discount_value,
                        'formatted_discount' => $voucher->formatted_discount,
                        'min_purchase' => $voucher->min_purchase,
                        'max_discount' => $voucher->max_discount,
                        'description' => $voucher->description,
                        'end_date' => $voucher->end_date->format('d M Y'),
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get available vouchers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil daftar voucher.'
            ], 500);
        }
    }
}