<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Activity;
use App\Models\Residence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    

    public function index()
    {
        $vouchers = Voucher::where('provider_id', auth()->id())
            ->with('discountable')
            ->latest()
            ->paginate(10);

        return view('provider.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $activities = Activity::where('provider_id', auth()->id())->get();
        $residences = Residence::where('provider_id', auth()->id())->get();

        return view('provider.vouchers.create', compact('activities', 'residences'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discountable_type' => 'required|in:App\Models\Activity,App\Models\Residence',
            'discountable_id' => 'required|integer',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
        ]);

        // Custom validation for percentage discount
        $validator->after(function ($validator) use ($request) {
            if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
                $validator->errors()->add('discount_value', 'Diskon persentase tidak boleh lebih dari 100%.');
            }
            
            // Validate that the selected item belongs to the provider
            if ($request->discountable_type && $request->discountable_id) {
                $discountableType = $request->discountable_type;
                $item = $discountableType::where('provider_id', auth()->id())
                    ->find($request->discountable_id);
                    
                if (!$item) {
                    $validator->errors()->add('discountable_id', 'Item yang dipilih tidak ditemukan atau tidak milik Anda.');
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Verify that the discountable item belongs to the provider
            $discountableType = $request->discountable_type;
            $discountable = $discountableType::where('provider_id', auth()->id())
                ->findOrFail($request->discountable_id);

            // Generate unique voucher code
            $attempts = 0;
            do {
                $code = strtoupper(Str::random(8));
                $attempts++;
                if ($attempts > 100) {
                    throw new \Exception('Gagal membuat kode voucher unik. Silakan coba lagi.');
                }
            } while (Voucher::where('code', $code)->exists());

            $voucher = Voucher::create([
                'code' => $code,
                'provider_id' => auth()->id(),
                'discountable_type' => $request->discountable_type,
                'discountable_id' => $request->discountable_id,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'min_purchase' => $request->min_purchase,
                'max_discount' => $request->max_discount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'usage_limit' => $request->usage_limit,
                'description' => $request->description,
                'is_active' => true,
            ]);

            return redirect()
                ->route('provider.vouchers.index')
                ->with('success', 'Voucher berhasil dibuat!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan voucher: ' . $e->getMessage());
        }
    }

    public function edit(Voucher $voucher)
    {
        // Check if voucher belongs to provider
        if ($voucher->provider_id !== auth()->id()) {
            abort(403);
        }

        $activities = Activity::where('provider_id', auth()->id())->get();
        $residences = Residence::where('provider_id', auth()->id())->get();

        return view('provider.vouchers.edit', compact('voucher', 'activities', 'residences'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        // Check if voucher belongs to provider
        if ($voucher->provider_id !== auth()->id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Custom validation for percentage discount
        $validator->after(function ($validator) use ($request, $voucher) {
            if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
                $validator->errors()->add('discount_value', 'Diskon persentase tidak boleh lebih dari 100%.');
            }
            
            // Check if voucher has been used
            if ($voucher->used_count > 0) {
                $validator->errors()->add('general', 'Voucher yang sudah digunakan tidak dapat diubah.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $voucher->update($request->all());

            return redirect()
                ->route('provider.vouchers.index')
                ->with('success', 'Voucher berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui voucher: ' . $e->getMessage());
        }
    }

    public function destroy(Voucher $voucher)
    {
        // Check if voucher belongs to provider
        if ($voucher->provider_id !== auth()->id()) {
            abort(403);
        }

        // Check if voucher has been used
        if ($voucher->used_count > 0) {
            return redirect()
                ->route('provider.vouchers.index')
                ->with('error', 'Voucher yang sudah digunakan tidak dapat dihapus.');
        }

        try {
            $voucher->delete();

            return redirect()
                ->route('provider.vouchers.index')
                ->with('success', 'Voucher berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('provider.vouchers.index')
                ->with('error', 'Terjadi kesalahan saat menghapus voucher: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Voucher $voucher)
    {
        // Check if voucher belongs to provider
        if ($voucher->provider_id !== auth()->id()) {
            abort(403);
        }

        // Check if voucher has been used
        if ($voucher->used_count > 0) {
            return redirect()
                ->route('provider.vouchers.index')
                ->with('error', 'Status voucher yang sudah digunakan tidak dapat diubah.');
        }

        try {
            $voucher->update(['is_active' => !$voucher->is_active]);

            return redirect()
                ->route('provider.vouchers.index')
                ->with('success', 'Status voucher berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('provider.vouchers.index')
                ->with('error', 'Terjadi kesalahan saat mengubah status voucher: ' . $e->getMessage());
        }
    }
} 