<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'type', 'icon', 'color', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function residences(): HasMany
    {
        return $this->hasMany(Residence::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeResidences($query)
    {
        return $query->where('type', 'residence');
    }

    public function scopeActivities($query)
    {
        return $query->where('type', 'activity');
    }
}
