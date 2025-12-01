<?php

use App\Services\TransferMatchingService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class extends Component {
    public function with(): array
    {
        $profile = auth()->user()->employeeProfile;
        $matchingService = new TransferMatchingService();
        $matches = $matchingService->getMatchesForProfile($profile, 6);

        return [
            'profile' => $profile,
            'matches' => $matches,
            'pendingIncoming' => $profile->pendingIncomingRequestsCount(),
            'pendingOutgoing' => $profile->pendingOutgoingRequestsCount(),
            'acceptedRequests' => $profile->outgoingTransferRequests()->accepted()->count() +
                                  $profile->incomingTransferRequests()->accepted()->count(),
        ];
    }
}; ?>

<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Welcome back, {{ $profile->first_name }}!</h1>
        <p class="mt-1 text-zinc-600 dark:text-zinc-400">Here's your transfer activity overview.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <flux:icon name="users" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $matches->count() }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Matches</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <flux:icon name="inbox" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $pendingIncoming }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Incoming</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <flux:icon name="paper-airplane" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $pendingOutgoing }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Outgoing</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <flux:icon name="check-circle" class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $acceptedRequests }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Connected</p>
                </div>
            </div>
        </div>
    </div>

    @if(!$profile->is_available_for_transfer)
        <div class="mb-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <div class="flex items-center">
                <flux:icon name="exclamation-triangle" class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-3" />
                <div>
                    <p class="font-medium text-yellow-800 dark:text-yellow-300">You're currently hidden from search results</p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-400">
                        <a href="{{ route('profile.edit') }}" class="underline">Update your profile</a>
                        to become visible to other employees.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Recent Matches</h2>
                <a href="{{ route('transfers.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    View All
                </a>
            </div>

            @if($matches->isEmpty())
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-8 text-center">
                    <div class="mx-auto w-12 h-12 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                        <flux:icon name="users" class="w-6 h-6 text-zinc-400" />
                    </div>
                    <h3 class="font-medium text-zinc-900 dark:text-white mb-2">No matches yet</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        We'll notify you when employees matching your preferences register.
                    </p>
                    <a href="{{ route('transfers.search') }}" class="inline-flex items-center px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        Search All Profiles
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($matches as $match)
                        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-3">
                                @if($match->profile_picture_url)
                                    <img src="{{ $match->profile_picture_url }}" alt="{{ $match->display_name }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <span class="font-bold text-blue-600 dark:text-blue-400">
                                            {{ substr($match->first_name, 0, 1) }}{{ substr($match->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <h3 class="font-medium text-zinc-900 dark:text-white text-sm">{{ $match->display_name }}</h3>
                                    <p class="text-xs text-zinc-500">{{ $match->current_location }}</p>
                                </div>
                            </div>
                            <a href="{{ route('transfers.show', $match) }}" class="block w-full text-center px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                View Details
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('transfers.search') }}" class="flex items-center w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <flux:icon name="magnifying-glass" class="w-4 h-4 mr-2" />
                        Search Transfers
                    </a>
                    <a href="{{ route('requests.incoming') }}" class="flex items-center w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <flux:icon name="inbox" class="w-4 h-4 mr-2" />
                        View Requests
                        @if($pendingIncoming > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingIncoming }}</span>
                        @endif
                    </a>
                    <a href="{{ route('profile.show') }}" class="flex items-center w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <flux:icon name="user" class="w-4 h-4 mr-2" />
                        My Profile
                    </a>
                </div>
            </div>

            <!-- Current Location -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-3">Your Location</h3>
                <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                    <flux:icon name="map-pin" class="w-5 h-5 mr-2" />
                    {{ $profile->current_location }}
                </div>
                <p class="text-sm text-zinc-500 mt-2">{{ $profile->employer->name }}</p>
            </div>

            <!-- Transfer Preferences -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-zinc-900 dark:text-white">Looking For</h3>
                    <a href="{{ route('profile.edit') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                </div>
                <div class="space-y-2">
                    @foreach($profile->preferredLocations as $location)
                        <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                            <span class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs flex items-center justify-center mr-2">
                                {{ $location->priority }}
                            </span>
                            {{ $location->location_name }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
