<?php

use App\Models\ActivityLog;
use App\Models\Employer;
use App\Models\EmployeeProfile;
use App\Models\JobTitle;
use App\Models\PreferredLocation;
use App\Models\Region;
use App\Models\Town;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[Layout('components.layouts.app')]
#[Title('Create Your Profile')]
class extends Component {
    use WithFileUploads;

    // Step tracking
    public int $currentStep = 1;
    public bool $eligibilityConfirmed = false;
    public string $probationStatus = '';

    // Profile fields
    #[Validate('required|string|max:255')]
    public string $first_name = '';

    #[Validate('required|string|max:255')]
    public string $last_name = '';

    #[Validate('nullable|string|max:255')]
    public string $employee_number = '';

    #[Validate('required|string|max:255')]
    public string $job_grade = '';

    #[Validate('required|exists:job_titles,id')]
    public string $job_title_id = '';

    #[Validate('required|exists:employers,id')]
    public string $employer_id = '';

    #[Validate('required|exists:regions,id')]
    public string $current_region_id = '';

    #[Validate('required|exists:towns,id')]
    public string $current_town_id = '';

    #[Validate('required|string|max:20')]
    public string $contact_number = '';

    #[Validate('nullable|string|max:20')]
    public string $alternative_contact_number = '';

    #[Validate('required|in:email,phone,both')]
    public string $preferred_communication = 'email';

    #[Validate('nullable|image|max:2048')]
    public $profile_picture = null;

    // Preferred locations
    public array $preferred_locations = [
        1 => ['region_id' => '', 'town_id' => ''],
        2 => ['region_id' => '', 'town_id' => ''],
        3 => ['region_id' => '', 'town_id' => ''],
    ];

    public function mount(): void
    {
        // If user already has a profile, redirect
        if (auth()->user()->hasProfile()) {
            $this->redirect(route('dashboard'));
        }
    }

    public function checkEligibility(): void
    {
        if ($this->probationStatus !== 'completed') {
            $this->addError('probationStatus', 'You must have completed your probation period to use this portal.');
            return;
        }

        $this->eligibilityConfirmed = true;
        $this->currentStep = 2;
    }

    public function updatedCurrentRegionId(): void
    {
        $this->current_town_id = '';
    }

    public function updatedPreferredLocations($value, $key): void
    {
        // When region changes, reset the town
        if (str_ends_with($key, '.region_id')) {
            $priority = explode('.', $key)[0];
            $this->preferred_locations[$priority]['town_id'] = '';
        }
    }

    public function goToStep(int $step): void
    {
        if ($step === 2 && !$this->eligibilityConfirmed) {
            return;
        }

        if ($step === 3) {
            $this->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'employer_id' => 'required|exists:employers,id',
                'current_region_id' => 'required|exists:regions,id',
                'current_town_id' => 'required|exists:towns,id',
                'contact_number' => 'required|string|max:20',
                'preferred_communication' => 'required|in:email,phone,both',
            ]);
        }

        $this->currentStep = $step;
    }

    public function save(): void
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employer_id' => 'required|exists:employers,id',
            'current_region_id' => 'required|exists:regions,id',
            'current_town_id' => 'required|exists:towns,id',
            'contact_number' => 'required|string|max:20',
            'preferred_communication' => 'required|in:email,phone,both',
        ]);

        // Validate at least one preferred location
        $hasLocation = false;
        foreach ($this->preferred_locations as $location) {
            if (!empty($location['region_id'])) {
                $hasLocation = true;
                break;
            }
        }

        if (!$hasLocation) {
            $this->addError('preferred_locations', 'Please select at least one preferred transfer location.');
            return;
        }

        $profile = DB::transaction(function () {
            // Handle profile picture upload
            $profilePicturePath = null;
            if ($this->profile_picture) {
                $profilePicturePath = $this->profile_picture->store('profile-pictures', 'public');
            }

            // Create profile
            $profile = EmployeeProfile::create([
                'user_id' => auth()->id(),
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'employee_number' => $this->employee_number ?: null,
                'job_grade' => $this->job_grade,
                'job_title_id' => $this->job_title_id,
                'employer_id' => $this->employer_id,
                'current_region_id' => $this->current_region_id,
                'current_town_id' => $this->current_town_id,
                'contact_number' => $this->contact_number,
                'alternative_contact_number' => $this->alternative_contact_number ?: null,
                'preferred_communication' => $this->preferred_communication,
                'profile_picture' => $profilePicturePath,
                'probation_status' => 'completed',
                'is_available_for_transfer' => true,
            ]);

            // Create preferred locations
            foreach ($this->preferred_locations as $priority => $location) {
                if (!empty($location['region_id'])) {
                    PreferredLocation::create([
                        'employee_profile_id' => $profile->id,
                        'region_id' => $location['region_id'],
                        'town_id' => $location['town_id'] ?: null,
                        'priority' => $priority,
                    ]);
                }
            }

            // Update user name
            auth()->user()->update([
                'name' => $this->first_name . ' ' . $this->last_name,
            ]);

            ActivityLog::log('profile_created', 'Profile created successfully');

            return $profile;
        });

        // Notify matched users about this new profile
        $profile->load('preferredLocations');
        app(NotificationService::class)->notifyMatchesForProfile($profile);

        session()->flash('status', 'Your profile has been created successfully!');
        $this->redirect(route('dashboard'));
    }

    public function with(): array
    {
        $towns = [];
        if ($this->current_region_id) {
            $towns = Town::where('region_id', $this->current_region_id)->active()->orderBy('name')->get();
        }

        $preferredTowns = [];
        foreach ($this->preferred_locations as $priority => $location) {
            if (!empty($location['region_id'])) {
                $preferredTowns[$priority] = Town::where('region_id', $location['region_id'])->active()->orderBy('name')->get();
            } else {
                $preferredTowns[$priority] = collect();
            }
        }

        return [
            'regions' => Region::active()->orderBy('name')->get(),
            'employers' => Employer::active()->orderBy('name')->get(),
            'jobTitles' => JobTitle::active()->orderBy('name')->get(),
            'towns' => $towns,
            'preferredTowns' => $preferredTowns,
        ];
    }
}; ?>

<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create Your Profile</h1>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">Complete your profile to start finding transfer matches.</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            @foreach([1 => 'Eligibility', 2 => 'Profile Details', 3 => 'Transfer Preferences'] as $step => $label)
                <div class="flex items-center {{ $step < 3 ? 'flex-1' : '' }}">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-2
                        {{ $currentStep >= $step ? 'bg-blue-600 border-blue-600 text-white' : 'border-zinc-300 dark:border-zinc-600 text-zinc-500' }}">
                        @if($currentStep > $step)
                            <flux:icon name="check" class="w-5 h-5" />
                        @else
                            {{ $step }}
                        @endif
                    </div>
                    <span class="ml-2 text-sm font-medium {{ $currentStep >= $step ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-500' }}">
                        {{ $label }}
                    </span>
                    @if($step < 3)
                        <div class="flex-1 h-0.5 mx-4 {{ $currentStep > $step ? 'bg-blue-600' : 'bg-zinc-300 dark:bg-zinc-600' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Step 1: Eligibility Check -->
    @if($currentStep === 1)
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Eligibility Check</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                Before creating your profile, please confirm your current employment status.
            </p>

            <div class="mb-6">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">
                    What is your current probation status?
                </label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <input type="radio" wire:model.live="probationStatus" value="completed" class="h-4 w-4 text-blue-600">
                        <span class="ml-3 text-zinc-900 dark:text-white">I have completed my probation period</span>
                    </label>
                    <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <input type="radio" wire:model.live="probationStatus" value="on_probation" class="h-4 w-4 text-blue-600">
                        <span class="ml-3 text-zinc-900 dark:text-white">I am currently on probation</span>
                    </label>
                    <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <input type="radio" wire:model.live="probationStatus" value="sick_leave" class="h-4 w-4 text-blue-600">
                        <span class="ml-3 text-zinc-900 dark:text-white">I am on sick leave without clearance</span>
                    </label>
                    <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <input type="radio" wire:model.live="probationStatus" value="rehabilitation" class="h-4 w-4 text-blue-600">
                        <span class="ml-3 text-zinc-900 dark:text-white">I am in rehabilitation</span>
                    </label>
                    <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <input type="radio" wire:model.live="probationStatus" value="under_investigation" class="h-4 w-4 text-blue-600">
                        <span class="ml-3 text-zinc-900 dark:text-white">I am under investigation</span>
                    </label>
                </div>
                @error('probationStatus')
                    <div class="mt-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                            Unfortunately, employees who are on probation, sick leave without clearance, in rehabilitation,
                            or under investigation are not eligible to use this portal at this time.
                        </p>
                    </div>
                @enderror
            </div>

            <div class="flex justify-end">
                <flux:button wire:click="checkEligibility" variant="primary" :disabled="!$probationStatus">
                    Continue
                </flux:button>
            </div>
        </div>
    @endif

    <!-- Step 2: Profile Details -->
    @if($currentStep === 2)
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Profile Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input
                    wire:model="first_name"
                    label="First Name"
                    required
                    placeholder="Enter your first name"
                />

                <flux:input
                    wire:model="last_name"
                    label="Last Name"
                    required
                    placeholder="Enter your last name"
                />

                <flux:input
                    wire:model="employee_number"
                    label="Employee Number"
                    placeholder="Optional"
                />

                <flux:select wire:model="job_grade" label="Job Grade" required>
                    <option value="">Select job grade</option>
                    @for($i = 1; $i <= 13; $i++)
                        <option value="{{ $i }}">Grade {{ $i }}</option>
                    @endfor
                </flux:select>

                <div class="md:col-span-2">
                    <flux:select wire:model="job_title_id" label="Job Title" required>
                        <option value="">Select job title</option>
                        @foreach($jobTitles as $jobTitle)
                            <option value="{{ $jobTitle->id }}">{{ $jobTitle->display_name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="md:col-span-2">
                    <flux:select wire:model="employer_id" label="Current Employer" required>
                        <option value="">Select your employer</option>
                        @foreach($employers as $employer)
                            <option value="{{ $employer->id }}">{{ $employer->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <flux:select wire:model.live="current_region_id" label="Current Region" required>
                    <option value="">Select region</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="current_town_id" label="Current Town" required :disabled="!$current_region_id">
                    <option value="">Select town</option>
                    @foreach($towns as $town)
                        <option value="{{ $town->id }}">{{ $town->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input
                    wire:model="contact_number"
                    label="Contact Number"
                    required
                    placeholder="+264 81 234 5678"
                />

                <flux:input
                    wire:model="alternative_contact_number"
                    label="Alternative Contact Number"
                    placeholder="Optional"
                />

                <div class="md:col-span-2">
                    <flux:select wire:model="preferred_communication" label="Preferred Communication Method" required>
                        <option value="email">Email</option>
                        <option value="phone">Phone</option>
                        <option value="both">Both Email and Phone</option>
                    </flux:select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Profile Picture (Optional)
                    </label>
                    <input
                        type="file"
                        wire:model="profile_picture"
                        accept="image/jpeg,image/png"
                        class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-zinc-700 dark:file:text-zinc-300"
                    >
                    <p class="mt-1 text-xs text-zinc-500">Max 2MB, JPG or PNG only</p>
                    @error('profile_picture') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if($profile_picture)
                        <div class="mt-4">
                            <img src="{{ $profile_picture->temporaryUrl() }}" class="w-24 h-24 rounded-full object-cover">
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <flux:button wire:click="goToStep(1)" variant="ghost">
                    Back
                </flux:button>
                <flux:button wire:click="goToStep(3)" variant="primary">
                    Continue
                </flux:button>
            </div>
        </div>
    @endif

    <!-- Step 3: Transfer Preferences -->
    @if($currentStep === 3)
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Transfer Preferences</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                Select up to 3 locations where you would like to be transferred to, in order of preference.
            </p>

            @error('preferred_locations')
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                </div>
            @enderror

            @foreach([1, 2, 3] as $priority)
                <div class="mb-6 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                    <h3 class="font-medium text-zinc-900 dark:text-white mb-4">
                        {{ $priority === 1 ? '1st' : ($priority === 2 ? '2nd' : '3rd') }} Choice
                        @if($priority === 1) <span class="text-red-500">*</span> @endif
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:select
                            wire:model.live="preferred_locations.{{ $priority }}.region_id"
                            label="Region"
                        >
                            <option value="">Select region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </flux:select>

                        <flux:select
                            wire:model="preferred_locations.{{ $priority }}.town_id"
                            label="Town (Optional)"
                            :disabled="!$preferred_locations[$priority]['region_id']"
                        >
                            <option value="">Any town in region</option>
                            @foreach($preferredTowns[$priority] as $town)
                                <option value="{{ $town->id }}">{{ $town->name }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-between mt-8">
                <flux:button wire:click="goToStep(2)" variant="ghost">
                    Back
                </flux:button>
                <flux:button wire:click="save" variant="primary">
                    Create Profile
                </flux:button>
            </div>
        </div>
    @endif
</div>
