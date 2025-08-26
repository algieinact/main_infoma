<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'provider_id',
        'discountable_type',
        'discountable_id',
        'discount_type', // percentage or fixed
        'discount_value',
        'min_purchase',
        'max_discount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active',
        'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereRaw('used_count < usage_limit');
            });
    }

    // Methods
    public function isValid(): bool
    {
        return $this->is_active &&
            now()->between($this->start_date, $this->end_date) &&
            (!$this->usage_limit || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount(float $amount): float
    {
        if (!$this->isValid() || $amount < $this->min_purchase) {
            return 0;
        }

        $discount = $this->discount_type === 'percentage'
            ? ($amount * $this->discount_value / 100)
            : $this->discount_value;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return $discount;
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    public function getFormattedDiscountAttribute(): string
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        }
        return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if (now() < $this->start_date) {
            return 'Pending';
        }
        
        if (now() > $this->end_date) {
            return 'Expired';
        }
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return 'Used Up';
        }
        
        return 'Active';
    }
} 