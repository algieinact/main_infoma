<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'discountable_type', 'discountable_id', 'name', 'code', 'description',
        'amount', 'is_percentage', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_percentage' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }
}
