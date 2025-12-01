<?php

namespace App\Services;

use App\Models\EmployeeProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TransferMatchingService
{
    public function getMatchesForProfile(EmployeeProfile $profile, int $limit = 20): Collection
    {
        // Get user's preferred locations
        $preferredRegionIds = $profile->preferredLocations->pluck('region_id')->toArray();
        $preferredTownIds = $profile->preferredLocations->pluck('town_id')->filter()->toArray();

        // Find employees who:
        // 1. Are in one of user's preferred locations (their current location)
        // 2. Have user's current location in their preferred locations
        // 3. Are available for transfers
        // 4. Have completed probation
        // 5. Have the SAME employer
        // 6. Have the SAME job grade
        // 7. Have the SAME job title

        $matches = EmployeeProfile::with(['user', 'employer', 'currentRegion', 'currentTown', 'preferredLocations.region', 'preferredLocations.town'])
            ->where('id', '!=', $profile->id)
            ->where('is_available_for_transfer', true)
            ->where('probation_status', 'completed')
            // Strict matching: same employer, job grade, and job title
            ->where('employer_id', $profile->employer_id)
            ->where('job_grade', $profile->job_grade)
            ->where('job_title_id', $profile->job_title_id)
            ->where(function (Builder $query) use ($preferredRegionIds, $preferredTownIds) {
                // Their current location is in my preferred regions
                $query->whereIn('current_region_id', $preferredRegionIds);

                // If I have specific towns, prioritize those
                if (!empty($preferredTownIds)) {
                    $query->orWhereIn('current_town_id', $preferredTownIds);
                }
            })
            ->whereHas('preferredLocations', function (Builder $query) use ($profile) {
                // They want my current region
                $query->where('region_id', $profile->current_region_id)
                    // And either any town in my region OR specifically my town
                    ->where(function (Builder $q) use ($profile) {
                        $q->whereNull('town_id')
                            ->orWhere('town_id', $profile->current_town_id);
                    });
            })
            ->get()
            ->map(function ($match) use ($profile, $preferredTownIds) {
                // Calculate match score
                $score = $this->calculateMatchScore($profile, $match, $preferredTownIds);
                $match->match_score = $score;
                return $match;
            })
            ->sortByDesc('match_score')
            ->take($limit);

        return $matches;
    }

    public function searchProfiles(
        EmployeeProfile $currentProfile,
        ?int $regionId = null,
        ?int $townId = null,
        ?int $employerId = null,
        ?string $jobGrade = null,
        ?string $jobTitle = null,
        ?string $keyword = null,
        int $perPage = 20
    ) {
        $query = EmployeeProfile::with(['user', 'employer', 'currentRegion', 'currentTown', 'preferredLocations.region', 'preferredLocations.town'])
            ->where('id', '!=', $currentProfile->id)
            ->where('is_available_for_transfer', true)
            ->where('probation_status', 'completed');

        if ($regionId) {
            $query->where('current_region_id', $regionId);
        }

        if ($townId) {
            $query->where('current_town_id', $townId);
        }

        if ($employerId) {
            $query->where('employer_id', $employerId);
        }

        if ($jobGrade) {
            $query->where('job_grade', $jobGrade);
        }

        if ($jobTitle) {
            $query->where('job_title_id', $jobTitle);
        }

        if ($keyword) {
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('job_grade', 'like', "%{$keyword}%")
                    ->orWhereHas('jobTitle', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    private function calculateMatchScore(EmployeeProfile $myProfile, EmployeeProfile $theirProfile, array $myPreferredTownIds): int
    {
        $score = 0;

        // Check if their current location is exactly in my preferred list
        $myPreferredLocations = $myProfile->preferredLocations;

        foreach ($myPreferredLocations as $preference) {
            // Exact town match = highest priority
            if ($preference->town_id && $preference->town_id === $theirProfile->current_town_id) {
                $score += (4 - $preference->priority) * 30; // 1st choice = 90, 2nd = 60, 3rd = 30
            }
            // Region match only = lower priority
            elseif ($preference->region_id === $theirProfile->current_region_id) {
                $score += (4 - $preference->priority) * 15; // 1st choice = 45, 2nd = 30, 3rd = 15
            }
        }

        // Check if my current location is exactly in their preferred list
        $theirPreferredLocations = $theirProfile->preferredLocations;

        foreach ($theirPreferredLocations as $preference) {
            // Exact town match from their side
            if ($preference->town_id && $preference->town_id === $myProfile->current_town_id) {
                $score += (4 - $preference->priority) * 30;
            }
            // Region match from their side
            elseif ($preference->region_id === $myProfile->current_region_id) {
                $score += (4 - $preference->priority) * 15;
            }
        }

        return $score;
    }

    public function isMutualMatch(EmployeeProfile $profile1, EmployeeProfile $profile2): bool
    {
        // First check strict requirements: same employer, job grade, and job title
        if ($profile1->employer_id !== $profile2->employer_id) {
            return false;
        }

        if ($profile1->job_grade !== $profile2->job_grade) {
            return false;
        }

        if ($profile1->job_title_id !== $profile2->job_title_id) {
            return false;
        }

        // Check if both are available
        if (!$profile1->is_available_for_transfer || !$profile2->is_available_for_transfer) {
            return false;
        }

        if ($profile1->probation_status !== 'completed' || $profile2->probation_status !== 'completed') {
            return false;
        }

        // Check if profile1's current location is in profile2's preferred
        $profile2WantsProfile1Location = $profile2->preferredLocations()
            ->where(function ($query) use ($profile1) {
                $query->where('region_id', $profile1->current_region_id)
                    ->where(function ($q) use ($profile1) {
                        $q->whereNull('town_id')
                            ->orWhere('town_id', $profile1->current_town_id);
                    });
            })
            ->exists();

        // Check if profile2's current location is in profile1's preferred
        $profile1WantsProfile2Location = $profile1->preferredLocations()
            ->where(function ($query) use ($profile2) {
                $query->where('region_id', $profile2->current_region_id)
                    ->where(function ($q) use ($profile2) {
                        $q->whereNull('town_id')
                            ->orWhere('town_id', $profile2->current_town_id);
                    });
            })
            ->exists();

        return $profile2WantsProfile1Location && $profile1WantsProfile2Location;
    }
}
