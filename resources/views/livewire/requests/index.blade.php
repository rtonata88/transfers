<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.app')]
#[Title('Transfer Requests')]
class extends Component {
    public function with(): array
    {
        $profile = auth()->user()->employeeProfile;

        return [
            'pendingIncoming' => $profile->pendingIncomingRequestsCount(),
            'pendingOutgoing' => $profile->pendingOutgoingRequestsCount(),
        ];
    }
}; ?>

<div class="max-w-4xl mx-auto py-8 px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Transfer Requests</h1>
            <p class="mt-1 text-zinc-600 dark:text-zinc-400">Manage your incoming and outgoing transfer requests</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Incoming Requests Card -->
            <a href="{{ route('requests.incoming') }}" wire:navigate
               class="block bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <flux:icon name="inbox" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    @if($pendingIncoming > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                            {{ $pendingIncoming }} pending
                        </span>
                    @endif
                </div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Incoming Requests</h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                    Requests from other employees who want to arrange a transfer with you.
                </p>
            </a>

            <!-- Outgoing Requests Card -->
            <a href="{{ route('requests.outgoing') }}" wire:navigate
               class="block bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <flux:icon name="paper-airplane" class="w-8 h-8 text-purple-600 dark:text-purple-400" />
                    </div>
                    @if($pendingOutgoing > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            {{ $pendingOutgoing }} pending
                        </span>
                    @endif
                </div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Outgoing Requests</h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                    Requests you've sent to other employees for transfer arrangements.
                </p>
            </a>
        </div>
</div>
