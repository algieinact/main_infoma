<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id', 'category_id', 'title', 'slug', 'description', 'type',
        'price', 'is_free', 'location', 'city', 'province', 'format', 
        'meeting_link', 'start_date', 'end_date', 'registration_deadline',
        'requirements', 'benefits', 'images', 'max_participants', 
        'current_participants', 'rating', 'total_reviews', 'is_active', 'is_featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'requirements' => 'array',
        'benefits' => 'array',
        'images' => 'array',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
        'rating' => 'decimal:2',
        'total_reviews' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function discounts(): MorphMany
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

    public function vouchers()
    {
        return $this->morphMany(\App\Models\Voucher::class, 'discountable');
    }

    public function userActivities(): MorphMany
    {
        return $this->morphMany(UserActivity::class, 'activityable');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOpenForRegistration($query)
    {
        return $query->where('registration_deadline', '>', now());
    }

    public function scopeAvailable($query)
    {
        return $query->where('current_participants', '<', $query->getModel()->max_participants);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByFormat($query, $format)
    {
        return $query->where('format', $format);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePaid($query)
    {
        return $query->where('is_free', false);
    }

    // Methods
    public function getFirstImageAttribute(): ?string
    {
        $images = $this->images ?? [];
        return !empty($images) ? asset('storage/' . $images[0]) : null;
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->is_free ? 'Gratis' : 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function isRegistrationOpen(): bool
    {
        return $this->registration_deadline > now() && 
               $this->current_participants < $this->max_participants;
    }

    public function getRemainingSeatsAttribute(): int
    {
        return $this->max_participants - $this->current_participants;
    }

    public function increaseParticipants(): void
    {
        $this->increment('current_participants');
    }

    public function decreaseParticipants(): void
    {
        $this->decrement('current_participants');
    }

    public function isBookmarkedBy(User $user)
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }
}
