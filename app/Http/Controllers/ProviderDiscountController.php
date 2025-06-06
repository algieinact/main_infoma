<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Discount;
use App\Models\Residence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class ProviderDiscountController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'provider']);
    }

    public function index()
    {
        $discounts = Discount::whereHasMorph('discountable', [Residence::class, Activity::class], function ($query) {
            $query->where('provider_id', Auth::id());
        })->paginate(10);

        return view('provider.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $residences = Residence::where('provider_id', Auth::id())->get();
        $activities = Activity::where('provider_id', Auth::id())->get();

        return view('provider.discounts.create', compact('residences', 'activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts,code',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'is_percentage' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'min_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'discountable_type' => 'required|in:App\Models\Residence,App\Models\Activity',
            'discountable_id' => 'required|integer',
        ]);

        // Verify that the discountable item belongs to the provider
        $discountableModel = $validated['discountable_type'];
        $discountable = $discountableModel::findOrFail($validated['discountable_id']);

        if ($discountable->provider_id !== Auth::id()) {
            abort(403);
        }

        $discount = Discount::create($validated);

        return redirect()->route('provider.discounts.index')
            ->with('success', 'Diskon berhasil dibuat.');
    }

    public function edit(Discount $discount)
    {
        // Check if the discount belongs to the provider
        if ($discount->discountable->provider_id !== Auth::id()) {
            abort(403);
        }

        $residences = Residence::where('provider_id', Auth::id())->get();
        $activities = Activity::where('provider_id', Auth::id())->get();

        return view('provider.discounts.edit', compact('discount', 'residences', 'activities'));
    }

    public function update(Request $request, Discount $discount)
    {
        // Check if the discount belongs to the provider
        if ($discount->discountable->provider_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts,code,' . $discount->id,
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'is_percentage' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'min_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'discountable_type' => 'required|in:App\Models\Residence,App\Models\Activity',
            'discountable_id' => 'required|integer',
        ]);

        // Verify that the new discountable item belongs to the provider
        $discountableModel = $validated['discountable_type'];
        $discountable = $discountableModel::findOrFail($validated['discountable_id']);

        if ($discountable->provider_id !== Auth::id()) {
            abort(403);
        }

        $discount->update($validated);

        return redirect()->route('provider.discounts.index')
            ->with('success', 'Diskon berhasil diperbarui.');
    }

    public function destroy(Discount $discount)
    {
        if ($discount->discountable->provider_id !== Auth::id()) {
            abort(403);
        }

        $discount->delete();

        return redirect()->route('provider.discounts.index')
            ->with('success', 'Diskon berhasil dihapus.');
    }

    public function getItems(Request $request)
    {
        $type = $request->query('type');
        $items = [];

        if ($type === 'App\Models\Residence') {
            $items = Residence::where('provider_id', Auth::id())
                ->select('id', 'title')
                ->get();
        } elseif ($type === 'App\Models\Activity') {
            $items = Activity::where('provider_id', Auth::id())
                ->select('id', 'title')
                ->get();
        }

        return response()->json($items);
    }
} 