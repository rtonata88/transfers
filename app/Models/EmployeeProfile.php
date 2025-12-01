<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EmployeeProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'employee_number',
        'job_grade',
        'job_title_id',
        'employer_id',
        'current_region_id',
        'current_town_id',
        'contact_number',
        'alternative_contact_number',
        'preferred_communication',
        'profile_picture',
        'probation_status',
        'probation_notes',
        'is_available_for_transfer',
    ];

    protected function casts(): array
    {
        return [
            'is_available_for_transfer' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function currentRegion(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'current_region_id');
    }

    public function currentTown(): BelongsTo
    {
        return $this->belongsTo(Town::class, 'current_town_id');
    }

    public function preferredLocations(): HasMany
    {
        return $this->hasMany(PreferredLocation::class)->orderBy('priority');
    }

    public function outgoingTransferRequests(): HasMany
    {
        return $this->hasMany(TransferRequest::class, 'requester_id');
    }

    public function incomingTransferRequests(): HasMany
    {
        return $this->hasMany(TransferRequest::class, 'requestee_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->first_name} " . Str::substr($this->last_name, 0, 1) . '.';
    }

    public function getCurrentLocationAttribute(): string
    {
        return "{$this->currentTown->name}, {$this->currentRegion->name}";
    }

    public function getProfilePictureUrlAttribute(): ?string
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }

        return null;
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available_for_transfer', true)
            ->where('probation_status', 'completed');
    }

    public function scopeWithCompletedProbation($query)
    {
        return $query->where('probation_status', 'completed');
    }

    public function isEligibleForTransfer(): bool
    {
        return $this->probation_status === 'completed' && $this->is_available_for_transfer;
    }

    public function canCreateProfile(): bool
    {
        return $this->probation_status === 'completed';
    }

    public function pendingOutgoingRequestsCount(): int
    {
        return $this->outgoingTransferRequests()->where('status', 'pending')->count();
    }

    public function pendingIncomingRequestsCount(): int
    {
        return $this->incomingTransferRequests()->where('status', 'pending')->count();
    }

    public function hasPendingRequestTo(EmployeeProfile $profile): bool
    {
        return $this->outgoingTransferRequests()
            ->where('requestee_id', $profile->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function hasAcceptedRequestWith(EmployeeProfile $profile): bool
    {
        return $this->outgoingTransferRequests()
            ->where('requestee_id', $profile->id)
            ->where('status', 'accepted')
            ->exists()
            ||
            $this->incomingTransferRequests()
                ->where('requester_id', $profile->id)
                ->where('status', 'accepted')
                ->exists();
    }
}
