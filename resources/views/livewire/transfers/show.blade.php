<?php

use App\Models\ActivityLog;
use App\Models\EmployeeProfile;
use App\Models\SiteSetting;
use App\Models\TransferRequest;
use App\Services\NotificationService;
use App\Services\TransferMatchingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app')]
class extends Component {
    public EmployeeProfile $employeeProfile;
    public string $message = '';
    public bool $showRequestModal = false;

    #[Url]
    public string $action = '';

    public function mount(EmployeeProfile $profile): void
    {
        $this->employeeProfile = $profile;

        // Check if action=request was passed in URL
        if ($this->action === 'request') {
            $this->showRequestModal = true;
        }
    }

    public function openRequestModal(): void
    {
        $this->showRequestModal = true;
    }

    public function sendRequest(): void
    {
        $myProfile = auth()->user()->employeeProfile;

        // Validation checks
        if (!$myProfile->is_available_for_transfer) {
            $this->addError('request', 'You must be available for transfers to send requests.');
            return;
        }

        if (!$this->employeeProfile->is_available_for_transfer) {
            $this->addError('request', 'This employee is not currently available for transfers.');
            return;
        }

        if ($myProfile->hasPendingRequestTo($this->employeeProfile)) {
            $this->addError('request', 'You already have a pending request with this employee.');
            return;
        }

        // Check max pending requests
        $maxRequests = (int) SiteSetting::get('max_pending_requests', 5);
        if ($myProfile->pendingOutgoingRequestsCount() >= $maxRequests) {
            $this->addError('request', "You can only have {$maxRequests} pending requests at a time.");
            return;
        }

        // Create the request
        $expiryDays = (int) SiteSetting::get('request_expiry_days', 30);

        $transferRequest = TransferRequest::create([
            'requester_id' => $myProfile->id,
            'requestee_id' => $this->employeeProfile->id,
            'status' => 'pending',
            'message' => $this->message ?: null,
            'expires_at' => now()->addDays($expiryDays),
        ]);

        ActivityLog::log('transfer_requested', "Sent transfer request to {$this->employeeProfile->display_name}");

        // Send notification email
        app(NotificationService::class)->sendTransferRequestReceived($transferRequest);

        session()->flash('status', 'Transfer request sent successfully!');
        $this->showRequestModal = false;
        $this->redirect(route('requests.outgoing'));
    }

    public function with(): array
    {
        $myProfile = auth()->user()->employeeProfile;
        $matchingService = new TransferMatchingService();

        return [
            'myProfile' => $myProfile,
            'isMutualMatch' => $matchingService->isMutualMatch($myProfile, $this->employeeProfile),
            'hasPendingRequest' => $myProfile->hasPendingRequestTo($this->employeeProfile),
            'hasAcceptedRequest' => $myProfile->hasAcceptedRequestWith($this->employeeProfile),
            'acceptedRequest' => $this->getAcceptedRequest($myProfile),
        ];
    }

    private function getAcceptedRequest(EmployeeProfile $myProfile): ?TransferRequest
    {
        return TransferRequest::where(function ($query) use ($myProfile) {
                $query->where('requester_id', $myProfile->id)
                    ->where('requestee_id', $this->employeeProfile->id);
            })
            ->orWhere(function ($query) use ($myProfile) {
                $query->where('requester_id', $this->employeeProfile->id)
                    ->where('requestee_id', $myProfile->id);
            })
            ->where('status', 'accepted')
            ->first();
    }
}; ?>

<div>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="mb-6">
            <flux:button :href="route('transfers.index')" variant="ghost" icon="arrow-left" wire:navigate>
                Back to Matches
            </flux:button>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
            </div>
        @endif

        @error('request')
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            </div>
        @enderror

        @if($isMutualMatch)
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center">
                    <flux:icon name="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" />
                    <p class="text-sm font-medium text-green-700 dark:text-green-300">
                        Mutual Match! This employee wants to transfer to your area too.
                    </p>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        @if($employeeProfile->profile_picture_url)
                            <img src="{{ $employeeProfile->profile_picture_url }}" alt="{{ $employeeProfile->display_name }}"
                                 class="w-20 h-20 rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ substr($employeeProfile->first_name, 0, 1) }}{{ substr($employeeProfile->last_name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $employeeProfile->display_name }}</h1>
                            <p class="text-zinc-600 dark:text-zinc-400">{{ $employeeProfile->employer->name }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @if($employeeProfile->jobTitle)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $employeeProfile->jobTitle->name }}
                                    </span>
                                @endif
                                @if($employeeProfile->job_grade)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
                                        Grade {{ $employeeProfile->job_grade }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($hasAcceptedRequest)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <flux:icon name="check-circle" class="w-4 h-4 mr-1" />
                            Connected
                        </span>
                    @elseif($hasPendingRequest)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            <flux:icon name="clock" class="w-4 h-4 mr-1" />
                            Request Pending
                        </span>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-6">
                <!-- Current Location -->
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">
                        Current Location
                    </h3>
                    <div class="flex items-center text-zinc-900 dark:text-white">
                        <flux:icon name="map-pin" class="w-5 h-5 mr-2 text-zinc-400" />
                        {{ $employeeProfile->current_location }}
                    </div>
                </div>

                <!-- Preferred Transfer Locations -->
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">
                        Wants to Transfer To
                    </h3>
                    <div class="space-y-2">
                        @foreach($employeeProfile->preferredLocations as $location)
                            <div class="flex items-center text-zinc-900 dark:text-white">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full
                                    {{ $location->region_id === $myProfile->current_region_id ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' }}
                                    text-sm font-medium mr-3">
                                    {{ $location->priority }}
                                </span>
                                <span>{{ $location->location_name }}</span>
                                @if($location->region_id === $myProfile->current_region_id)
                                    <span class="ml-2 text-xs text-green-600 dark:text-green-400">(Your region!)</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($hasAcceptedRequest && $acceptedRequest)
                    <!-- Contact Information (only shown when accepted) -->
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <h3 class="text-sm font-medium text-green-800 dark:text-green-300 uppercase tracking-wider mb-3">
                            Contact Information
                        </h3>
                        <p class="text-sm text-green-700 dark:text-green-400 mb-4">
                            Your transfer request has been accepted. You can now contact this employee directly to arrange your transfer.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center text-green-900 dark:text-green-100">
                                <flux:icon name="user" class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" />
                                {{ $employeeProfile->full_name }}
                            </div>
                            <div class="flex items-center text-green-900 dark:text-green-100">
                                <flux:icon name="envelope" class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" />
                                {{ $employeeProfile->user->email }}
                            </div>
                            <div class="flex items-center text-green-900 dark:text-green-100">
                                <flux:icon name="phone" class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" />
                                {{ $employeeProfile->contact_number }}
                            </div>
                            @if($employeeProfile->alternative_contact_number)
                                <div class="flex items-center text-green-900 dark:text-green-100">
                                    <flux:icon name="phone" class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" />
                                    {{ $employeeProfile->alternative_contact_number }} (Alt)
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Contact hidden message -->
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <div class="flex items-center text-zinc-600 dark:text-zinc-400">
                            <flux:icon name="lock-closed" class="w-5 h-5 mr-2" />
                            <span class="text-sm">Contact details are hidden until a transfer request is accepted.</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            @if(!$hasAcceptedRequest && !$hasPendingRequest)
                <div class="p-6 bg-zinc-50 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button wire:click="openRequestModal" variant="primary" class="w-full">
                        <flux:icon name="paper-airplane" class="w-4 h-4 mr-2" />
                        Send Transfer Request
                    </flux:button>
                </div>
            @endif
        </div>
    </div>

    <!-- Request Modal -->
    <flux:modal wire:model="showRequestModal" class="max-w-lg">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">
                Send Transfer Request
            </h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                You're about to send a transfer request to <strong>{{ $employeeProfile->display_name }}</strong>.
                They will be notified and can choose to accept or decline.
            </p>

            <div class="mb-6">
                <flux:textarea
                    wire:model="message"
                    label="Message (Optional)"
                    placeholder="Add a personal message to introduce yourself..."
                    rows="3"
                />
            </div>

            <div class="flex gap-4">
                <flux:button wire:click="$set('showRequestModal', false)" variant="ghost" class="flex-1">
                    Cancel
                </flux:button>
                <flux:button wire:click="sendRequest" variant="primary" class="flex-1">
                    Send Request
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
