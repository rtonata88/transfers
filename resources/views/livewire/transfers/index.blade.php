<?php

use App\Services\TransferMatchingService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.app')]
#[Title('Transfer Matches')]
class extends Component {
    public function with(): array
    {
        $profile = auth()->user()->employeeProfile;
        $matchingService = new TransferMatchingService();
        $matches = $matchingService->getMatchesForProfile($profile);

        return [
            'profile' => $profile,
            'matches' => $matches,
        ];
    }
}; ?>

<div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Transfer Matches</h1>
                <p class="mt-1 text-zinc-600 dark:text-zinc-400">
                    Employees who match your transfer preferences
                </p>
            </div>
            <flux:button :href="route('transfers.search')" variant="outline" icon="magnifying-glass" wire:navigate>
                Search All
            </flux:button>
        </div>

        @if($matches->isEmpty())
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                    <flux:icon name="users" class="w-8 h-8 text-zinc-400" />
                </div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-2">No matches found yet</h3>
                <p class="text-zinc-600 dark:text-zinc-400 max-w-md mx-auto mb-6">
                    We couldn't find employees that match your transfer preferences right now.
                    New employees register regularly, so check back later or try searching all available profiles.
                </p>
                <div class="flex justify-center gap-4">
                    <flux:button :href="route('transfers.search')" variant="primary" wire:navigate>
                        Search All Profiles
                    </flux:button>
                    <flux:button :href="route('profile.edit')" variant="outline" wire:navigate>
                        Update Preferences
                    </flux:button>
                </div>
            </div>
        @else
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    <strong>{{ $matches->count() }} potential matches found!</strong>
                    These employees are in locations you want to transfer to, and they want to transfer to your location.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($matches as $match)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    @if($match->profile_picture_url)
                                        <img src="{{ $match->profile_picture_url }}" alt="{{ $match->display_name }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                {{ substr($match->first_name, 0, 1) }}{{ substr($match->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $match->display_name }}</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $match->employer->abbreviation ?? $match->employer->name }}</p>
                                    </div>
                                </div>
                                @if($match->match_score >= 100)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Great Match
                                    </span>
                                @elseif($match->match_score >= 50)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        Good Match
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="map-pin" class="w-4 h-4 mr-2" />
                                    {{ $match->current_location }}
                                </div>
                                @if($match->jobTitle || $match->job_grade)
                                    <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                                        <flux:icon name="briefcase" class="w-4 h-4 mr-2" />
                                        {{ $match->jobTitle?->name }}@if($match->jobTitle && $match->job_grade) - @endif{{ $match->job_grade ? 'Grade ' . $match->job_grade : '' }}
                                    </div>
                                @endif
                            </div>

                            <div class="text-xs text-zinc-500 dark:text-zinc-500 mb-4">
                                Wants to go to:
                                @foreach($match->preferredLocations->take(2) as $pref)
                                    <span class="inline-block bg-zinc-100 dark:bg-zinc-800 rounded px-1.5 py-0.5 mr-1">
                                        {{ $pref->region->name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="flex gap-2">
                                <flux:button :href="route('transfers.show', $match)" variant="outline" class="flex-1" wire:navigate>
                                    View Details
                                </flux:button>
                                @if(!$profile->hasPendingRequestTo($match) && !$profile->hasAcceptedRequestWith($match))
                                    <flux:button :href="route('transfers.show', $match) . '?action=request'" variant="primary" class="flex-1" wire:navigate>
                                        Request Transfer
                                    </flux:button>
                                @elseif($profile->hasAcceptedRequestWith($match))
                                    <span class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-green-700 dark:text-green-400">
                                        Connected
                                    </span>
                                @else
                                    <span class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                        Request Sent
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
</div>
