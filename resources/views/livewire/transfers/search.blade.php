<?php

use App\Models\Employer;
use App\Models\Region;
use App\Models\Town;
use App\Services\TransferMatchingService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

new
#[Layout('components.layouts.app')]
#[Title('Search Transfers')]
class extends Component {
    use WithPagination;

    public string $region_id = '';
    public string $town_id = '';
    public string $employer_id = '';
    public string $job_grade = '';
    public string $keyword = '';

    public function updatedRegionId(): void
    {
        $this->town_id = '';
        $this->resetPage();
    }

    public function updated($property): void
    {
        if (in_array($property, ['town_id', 'employer_id', 'job_grade', 'keyword'])) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['region_id', 'town_id', 'employer_id', 'job_grade', 'keyword']);
        $this->resetPage();
    }

    public function with(): array
    {
        $profile = auth()->user()->employeeProfile;
        $matchingService = new TransferMatchingService();

        $results = $matchingService->searchProfiles(
            $profile,
            $this->region_id ?: null,
            $this->town_id ?: null,
            $this->employer_id ?: null,
            $this->job_grade ?: null,
            $this->keyword ?: null
        );

        $towns = [];
        if ($this->region_id) {
            $towns = Town::where('region_id', $this->region_id)->active()->orderBy('name')->get();
        }

        return [
            'profile' => $profile,
            'results' => $results,
            'regions' => Region::active()->orderBy('name')->get(),
            'towns' => $towns,
            'employers' => Employer::active()->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Search Transfers</h1>
                <p class="mt-1 text-zinc-600 dark:text-zinc-400">
                    Find all employees available for transfers
                </p>
            </div>
            <flux:button :href="route('transfers.index')" variant="outline" wire:navigate>
                View Matches Only
            </flux:button>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <flux:select wire:model.live="region_id" label="Region">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="town_id" label="Town" :disabled="!$region_id">
                    <option value="">All Towns</option>
                    @foreach($towns as $town)
                        <option value="{{ $town->id }}">{{ $town->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="employer_id" label="Employer">
                    <option value="">All Employers</option>
                    @foreach($employers as $employer)
                        <option value="{{ $employer->id }}">{{ $employer->abbreviation ?? $employer->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input
                    wire:model.live.debounce.300ms="job_grade"
                    label="Job Grade"
                    placeholder="e.g., Grade 8"
                />

                <flux:input
                    wire:model.live.debounce.300ms="keyword"
                    label="Keyword"
                    placeholder="Search..."
                />
            </div>

            @if($region_id || $town_id || $employer_id || $job_grade || $keyword)
                <div class="mt-4 flex justify-end">
                    <flux:button wire:click="clearFilters" variant="ghost" size="sm">
                        Clear Filters
                    </flux:button>
                </div>
            @endif
        </div>

        <!-- Results -->
        @if($results->isEmpty())
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                    <flux:icon name="magnifying-glass" class="w-8 h-8 text-zinc-400" />
                </div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-2">No results found</h3>
                <p class="text-zinc-600 dark:text-zinc-400">
                    Try adjusting your search filters to find more profiles.
                </p>
            </div>
        @else
            <div class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">
                Found {{ $results->total() }} employee{{ $results->total() !== 1 ? 's' : '' }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($results as $employee)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                @if($employee->profile_picture_url)
                                    <img src="{{ $employee->profile_picture_url }}" alt="{{ $employee->display_name }}"
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $employee->display_name }}</h3>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $employee->employer->abbreviation ?? $employee->employer->name }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="map-pin" class="w-4 h-4 mr-2" />
                                    {{ $employee->current_location }}
                                </div>
                                @if($employee->jobTitle || $employee->job_grade)
                                    <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                                        <flux:icon name="briefcase" class="w-4 h-4 mr-2" />
                                        {{ $employee->jobTitle?->name }}@if($employee->jobTitle && $employee->job_grade) - @endif{{ $employee->job_grade ? 'Grade ' . $employee->job_grade : '' }}
                                    </div>
                                @endif
                            </div>

                            <div class="text-xs text-zinc-500 dark:text-zinc-500 mb-4">
                                Wants to go to:
                                @foreach($employee->preferredLocations->take(2) as $pref)
                                    <span class="inline-block bg-zinc-100 dark:bg-zinc-800 rounded px-1.5 py-0.5 mr-1">
                                        {{ $pref->region->name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="flex gap-2">
                                <flux:button :href="route('transfers.show', $employee)" variant="outline" class="flex-1" wire:navigate>
                                    View Details
                                </flux:button>
                                @if(!$profile->hasPendingRequestTo($employee) && !$profile->hasAcceptedRequestWith($employee))
                                    <flux:button :href="route('transfers.show', $employee) . '?action=request'" variant="primary" class="flex-1" wire:navigate>
                                        Request
                                    </flux:button>
                                @elseif($profile->hasAcceptedRequestWith($employee))
                                    <span class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-green-700 dark:text-green-400">
                                        Connected
                                    </span>
                                @else
                                    <span class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                        Sent
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $results->links() }}
            </div>
        @endif
</div>
