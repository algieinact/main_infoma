<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'booking_id', 'reviewable_type', 'reviewable_id',
        'rating', 'comment', 'images', 'is_anonymous'
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_anonymous' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous User';
        }
        return $this->user->name;
    }

    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    public function getStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }
}
