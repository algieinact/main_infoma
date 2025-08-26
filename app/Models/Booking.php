<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_WAITING_PROVIDER_APPROVAL = 'waiting_provider_approval';
    const STATUS_PROVIDER_APPROVED = 'provider_approved';
    const STATUS_PROVIDER_REJECTED = 'provider_rejected';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'booking_code', 'user_id', 'bookable_type', 'bookable_id', 
        'booking_data', 'files', 'status', 'booking_date', 'start_date', 
        'end_date', 'total_amount', 'discount_amount', 'final_amount', 
        'voucher_id', 'notes', 'cancellation_reason', 'cancelled_at'
    ];

    protected $casts = [
        'booking_data' => 'array',
        'files' => 'array',
        'booking_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isWaitingProviderApproval(): bool
    {
        return $this->status === self::STATUS_WAITING_PROVIDER_APPROVAL;
    }

    public function isProviderApproved(): bool
    {
        return $this->status === self::STATUS_PROVIDER_APPROVED;
    }

    public function isProviderRejected(): bool
    {
        return $this->status === self::STATUS_PROVIDER_REJECTED;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function cancel(string $reason): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }

    public function confirm(): void
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
        ]);
    }

    public function reject(): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
        ]);
    }

    public function approve(): void
    {
        $this->update([
            'status' => self::STATUS_PROVIDER_APPROVED,
        ]);
    }

    public function rejectByProvider(): void
    {
        $this->update([
            'status' => self::STATUS_PROVIDER_REJECTED,
        ]);
    }

    public function applyDiscount(float $discountAmount): void
    {
        $this->update([
            'discount_amount' => $discountAmount,
            'final_amount' => $this->total_amount - $discountAmount,
        ]);
    }
}
