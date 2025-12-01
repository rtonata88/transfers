<?php

use App\Models\ActivityLog;
use App\Models\TransferRequest;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

new
#[Layout('components.layouts.app')]
#[Title('Outgoing Requests')]
class extends Component {
    use WithPagination;

    public string $filter = 'pending';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function cancel(TransferRequest $request): void
    {
        if ($request->requester_id !== auth()->user()->employeeProfile->id) {
            return;
        }

        if ($request->status !== 'pending') {
            return;
        }

        $request->cancel();

        ActivityLog::log('transfer_cancelled', "Cancelled transfer request to {$request->requestee->display_name}");

        session()->flash('status', 'Transfer request cancelled.');
    }

    public function with(): array
    {
        $profile = auth()->user()->employeeProfile;

        $query = TransferRequest::with(['requestee.user', 'requestee.employer', 'requestee.currentRegion', 'requestee.currentTown'])
            ->where('requester_id', $profile->id);

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10);

        return [
            'requests' => $requests,
            'counts' => [
                'pending' => TransferRequest::where('requester_id', $profile->id)->pending()->count(),
                'accepted' => TransferRequest::where('requester_id', $profile->id)->accepted()->count(),
                'declined' => TransferRequest::where('requester_id', $profile->id)->declined()->count(),
            ],
        ];
    }
}; ?>

<div class="max-w-4xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Outgoing Requests</h1>
                <p class="mt-1 text-zinc-600 dark:text-zinc-400">Requests you've sent to other employees</p>
            </div>
            <flux:button :href="route('requests.index')" variant="ghost" icon="arrow-left" wire:navigate>
                Back
            </flux:button>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="flex space-x-1 mb-6 bg-zinc-100 dark:bg-zinc-800 rounded-lg p-1">
            <button wire:click="$set('filter', 'pending')"
                    class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors
                           {{ $filter === 'pending' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                Pending ({{ $counts['pending'] }})
            </button>
            <button wire:click="$set('filter', 'accepted')"
                    class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors
                           {{ $filter === 'accepted' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                Accepted ({{ $counts['accepted'] }})
            </button>
            <button wire:click="$set('filter', 'declined')"
                    class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors
                           {{ $filter === 'declined' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                Declined ({{ $counts['declined'] }})
            </button>
        </div>

        @if($requests->isEmpty())
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                    <flux:icon name="paper-airplane" class="w-8 h-8 text-zinc-400" />
                </div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-2">No {{ $filter }} requests</h3>
                <p class="text-zinc-600 dark:text-zinc-400 mb-4">
                    @if($filter === 'pending')
                        You haven't sent any transfer requests yet.
                    @else
                        You don't have any {{ $filter }} requests.
                    @endif
                </p>
                @if($filter === 'pending')
                    <flux:button :href="route('transfers.index')" variant="primary" wire:navigate>
                        Find Transfer Matches
                    </flux:button>
                @endif
            </div>
        @else
            <div class="space-y-4">
                @foreach($requests as $request)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                @if($request->requestee->profile_picture_url)
                                    <img src="{{ $request->requestee->profile_picture_url }}" alt="{{ $request->requestee->display_name }}"
                                         class="w-14 h-14 rounded-full object-cover">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                            {{ substr($request->requestee->first_name, 0, 1) }}{{ substr($request->requestee->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h3 class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $request->status === 'accepted' ? $request->requestee->full_name : $request->requestee->display_name }}
                                    </h3>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $request->requestee->employer->name }}</p>
                                    <div class="flex items-center text-sm text-zinc-500 mt-1">
                                        <flux:icon name="map-pin" class="w-4 h-4 mr-1" />
                                        {{ $request->requestee->current_location }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $request->status === 'accepted' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $request->status === 'declined' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                    {{ $request->status === 'cancelled' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' : '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                <p class="text-xs text-zinc-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if($request->message)
                            <div class="mt-4 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 italic">Your message: "{{ $request->message }}"</p>
                            </div>
                        @endif

                        @if($request->status === 'accepted')
                            <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <h4 class="text-sm font-medium text-green-800 dark:text-green-300 mb-2">Contact Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                    <div class="flex items-center text-green-700 dark:text-green-400">
                                        <flux:icon name="envelope" class="w-4 h-4 mr-2" />
                                        {{ $request->requestee->user->email }}
                                    </div>
                                    <div class="flex items-center text-green-700 dark:text-green-400">
                                        <flux:icon name="phone" class="w-4 h-4 mr-2" />
                                        {{ $request->requestee->contact_number }}
                                    </div>
                                    @if($request->requestee->alternative_contact_number)
                                        <div class="flex items-center text-green-700 dark:text-green-400">
                                            <flux:icon name="phone" class="w-4 h-4 mr-2" />
                                            {{ $request->requestee->alternative_contact_number }} (Alt)
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($request->status === 'declined')
                            <div class="mt-4 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                    This request was declined. You can still search for other transfer matches.
                                </p>
                            </div>
                        @endif

                        @if($request->status === 'pending')
                            <div class="mt-4 flex gap-3">
                                <flux:button :href="route('transfers.show', $request->requestee)" variant="outline" class="flex-1" wire:navigate>
                                    View Profile
                                </flux:button>
                                <flux:button wire:click="cancel({{ $request->id }})" variant="ghost" icon="x-mark" class="flex-1 text-red-600 hover:text-red-700">
                                    Cancel Request
                                </flux:button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @endif
</div>
