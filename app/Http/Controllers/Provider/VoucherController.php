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
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify that the discountable item belongs to the provider
        $discountableType = $request->discountable_type;
        $discountable = $discountableType::where('provider_id', auth()->id())
            ->findOrFail($request->discountable_id);

        // Generate unique voucher code
        do {
            $code = strtoupper(Str::random(8));
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
        ]);

        return redirect()
            ->route('provider.vouchers.index')
            ->with('success', 'Voucher berhasil dibuat!');
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $voucher->update($request->all());

        return redirect()
            ->route('provider.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroy(Voucher $voucher)
    {
        // Check if voucher belongs to provider
        if ($voucher->provider_id !== auth()->id()) {
            abort(403);
        }

        $voucher->delete();

        return redirect()
            ->route('provider.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus!');
    }

    public function toggleStatus(Voucher $voucher)
    {
        // Check if voucher belongs to provider
        if ($voucher->provider_id !== auth()->id()) {
            abort(403);
        }

        $voucher->update(['is_active' => !$voucher->is_active]);

        return redirect()
            ->route('provider.vouchers.index')
            ->with('success', 'Status voucher berhasil diperbarui!');
    }
} 