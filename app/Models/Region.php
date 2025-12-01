<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function towns(): HasMany
    {
        return $this->hasMany(Town::class);
    }

    public function activeTowns(): HasMany
    {
        return $this->towns()->where('is_active', true);
    }

    public function employeeProfiles(): HasMany
    {
        return $this->hasMany(EmployeeProfile::class, 'current_region_id');
    }

    public function preferredLocations(): HasMany
    {
        return $this->hasMany(PreferredLocation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
