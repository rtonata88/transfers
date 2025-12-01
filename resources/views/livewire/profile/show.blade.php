<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.app')]
#[Title('My Profile')]
class extends Component {
    public function with(): array
    {
        $profile = auth()->user()->employeeProfile;

        return [
            'profile' => $profile,
            'pendingIncoming' => $profile->pendingIncomingRequestsCount(),
            'pendingOutgoing' => $profile->pendingOutgoingRequestsCount(),
        ];
    }
}; ?>

<div class="max-w-4xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">My Profile</h1>
                <p class="mt-1 text-zinc-600 dark:text-zinc-400">View and manage your transfer profile</p>
            </div>
            <flux:button :href="route('profile.edit')" variant="primary" icon="pencil" wire:navigate>
                Edit Profile
            </flux:button>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <flux:icon name="check-circle" class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Transfer Status</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">
                            {{ $profile->is_available_for_transfer ? 'Available' : 'Not Available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <flux:icon name="inbox" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Incoming Requests</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $pendingIncoming }} pending</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <flux:icon name="paper-airplane" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Outgoing Requests</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $pendingOutgoing }} pending</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center">
                    @if($profile->profile_picture_url)
                        <img src="{{ $profile->profile_picture_url }}" alt="{{ $profile->full_name }}"
                             class="w-20 h-20 rounded-full object-cover">
                    @else
                        <div class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ substr($profile->first_name, 0, 1) }}{{ substr($profile->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-6">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $profile->full_name }}</h2>
                        <p class="text-zinc-600 dark:text-zinc-400">{{ $profile->employer->name }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($profile->jobTitle)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $profile->jobTitle->name }}
                                </span>
                            @endif
                            @if($profile->job_grade)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
                                    Grade {{ $profile->job_grade }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Current Location -->
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">
                        Current Location
                    </h3>
                    <div class="flex items-center text-zinc-900 dark:text-white">
                        <flux:icon name="map-pin" class="w-5 h-5 mr-2 text-zinc-400" />
                        {{ $profile->current_location }}
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center text-zinc-900 dark:text-white">
                            <flux:icon name="envelope" class="w-5 h-5 mr-2 text-zinc-400" />
                            {{ auth()->user()->email }}
                        </div>
                        <div class="flex items-center text-zinc-900 dark:text-white">
                            <flux:icon name="phone" class="w-5 h-5 mr-2 text-zinc-400" />
                            {{ $profile->contact_number }}
                        </div>
                        @if($profile->alternative_contact_number)
                            <div class="flex items-center text-zinc-900 dark:text-white">
                                <flux:icon name="phone" class="w-5 h-5 mr-2 text-zinc-400" />
                                {{ $profile->alternative_contact_number }} (Alternative)
                            </div>
                        @endif
                        <div class="flex items-center text-zinc-900 dark:text-white">
                            <flux:icon name="chat-bubble-left-right" class="w-5 h-5 mr-2 text-zinc-400" />
                            Prefers: {{ ucfirst($profile->preferred_communication) }}
                        </div>
                    </div>
                </div>

                @if($profile->employee_number)
                    <!-- Employment Details -->
                    <div>
                        <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">
                            Employment Details
                        </h3>
                        <div class="flex items-center text-zinc-900 dark:text-white">
                            <flux:icon name="identification" class="w-5 h-5 mr-2 text-zinc-400" />
                            Employee #: {{ $profile->employee_number }}
                        </div>
                    </div>
                @endif

                <!-- Preferred Transfer Locations -->
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">
                        Preferred Transfer Locations
                    </h3>
                    <div class="space-y-2">
                        @foreach($profile->preferredLocations as $location)
                            <div class="flex items-center text-zinc-900 dark:text-white">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-medium mr-3">
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
