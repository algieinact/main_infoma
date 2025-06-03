<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Residence extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id', 'category_id', 'title', 'slug', 'description', 'type',
        'price', 'price_period', 'address', 'city', 'province', 'latitude', 
        'longitude', 'facilities', 'rules', 'images', 'total_rooms', 
        'available_rooms', 'gender_type', 'rating', 'total_reviews', 
        'is_active', 'is_featured', 'available_from'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'facilities' => 'array',
        'rules' => 'array',
        'images' => 'array',
        'total_rooms' => 'integer',
        'available_rooms' => 'integer',
        'rating' => 'decimal:2',
        'total_reviews' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'available_from' => 'datetime',
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

    public function scopeAvailable($query)
    {
        return $query->where('available_rooms', '>', 0);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // Methods
    public function getFirstImageAttribute(): ?string
    {
        $images = $this->images ?? [];
        return !empty($images) ? asset('storage/' . $images[0]) : null;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.') . '/' . $this->price_period;
    }

    public function isAvailable(): bool
    {
        return $this->available_rooms > 0 && $this->is_active;
    }

    public function decreaseAvailability(): void
    {
        $this->decrement('available_rooms');
    }

    public function increaseAvailability(): void
    {
        $this->increment('available_rooms');
    }

    public function isBookmarkedBy(User $user)
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }
}