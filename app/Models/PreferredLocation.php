<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferredLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_profile_id',
        'region_id',
        'town_id',
        'priority',
    ];

    public function employeeProfile(): BelongsTo
    {
        return $this->belongsTo(EmployeeProfile::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }

    public function getLocationNameAttribute(): string
    {
        if ($this->town) {
            return "{$this->town->name}, {$this->region->name}";
        }

        return "{$this->region->name} (Any town)";
    }
}
