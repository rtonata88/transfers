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
#[Title('Edit Profile')]
class extends Component {
    use WithFileUploads;

    public EmployeeProfile $profile;

    public string $first_name = '';
    public string $last_name = '';
    public string $employee_number = '';
    public string $job_grade = '';
    public string $job_title_id = '';
    public string $employer_id = '';
    public string $current_region_id = '';
    public string $current_town_id = '';
    public string $contact_number = '';
    public string $alternative_contact_number = '';
    public string $preferred_communication = 'email';
    public bool $is_available_for_transfer = true;
    public $new_profile_picture = null;
    public bool $remove_picture = false;

    public array $preferred_locations = [
        1 => ['region_id' => '', 'town_id' => ''],
        2 => ['region_id' => '', 'town_id' => ''],
        3 => ['region_id' => '', 'town_id' => ''],
    ];

    public function mount(): void
    {
        $this->profile = auth()->user()->employeeProfile;

        $this->first_name = $this->profile->first_name;
        $this->last_name = $this->profile->last_name;
        $this->employee_number = $this->profile->employee_number ?? '';
        $this->job_grade = $this->profile->job_grade ?? '';
        $this->job_title_id = $this->profile->job_title_id ? (string) $this->profile->job_title_id : '';
        $this->employer_id = (string) $this->profile->employer_id;
        $this->current_region_id = (string) $this->profile->current_region_id;
        $this->current_town_id = (string) $this->profile->current_town_id;
        $this->contact_number = $this->profile->contact_number;
        $this->alternative_contact_number = $this->profile->alternative_contact_number ?? '';
        $this->preferred_communication = $this->profile->preferred_communication;
        $this->is_available_for_transfer = $this->profile->is_available_for_transfer;

        // Load preferred locations
        foreach ($this->profile->preferredLocations as $location) {
            $this->preferred_locations[$location->priority] = [
                'region_id' => (string) $location->region_id,
                'town_id' => $location->town_id ? (string) $location->town_id : '',
            ];
        }
    }

    public function updatedCurrentRegionId(): void
    {
        $this->current_town_id = '';
    }

    public function updatedPreferredLocations($value, $key): void
    {
        if (str_ends_with($key, '.region_id')) {
            $priority = explode('.', $key)[0];
            $this->preferred_locations[$priority]['town_id'] = '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'job_grade' => 'required|string|max:255',
            'job_title_id' => 'required|exists:job_titles,id',
            'employer_id' => 'required|exists:employers,id',
            'current_region_id' => 'required|exists:regions,id',
            'current_town_id' => 'required|exists:towns,id',
            'contact_number' => 'required|string|max:20',
            'preferred_communication' => 'required|in:email,phone,both',
            'new_profile_picture' => 'nullable|image|max:2048',
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

        DB::transaction(function () {
            // Handle profile picture
            $profilePicturePath = $this->profile->profile_picture;

            if ($this->remove_picture && $profilePicturePath) {
                Storage::disk('public')->delete($profilePicturePath);
                $profilePicturePath = null;
            }

            if ($this->new_profile_picture) {
                if ($profilePicturePath) {
                    Storage::disk('public')->delete($profilePicturePath);
                }
                $profilePicturePath = $this->new_profile_picture->store('profile-pictures', 'public');
            }

            // Update profile
            $this->profile->update([
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
                'is_available_for_transfer' => $this->is_available_for_transfer,
            ]);

            // Update preferred locations
            $this->profile->preferredLocations()->delete();

            foreach ($this->preferred_locations as $priority => $location) {
                if (!empty($location['region_id'])) {
                    PreferredLocation::create([
                        'employee_profile_id' => $this->profile->id,
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

            ActivityLog::log('profile_updated', 'Profile updated');
        });

        // Notify matched users about this profile update
        $this->profile->refresh();
        app(NotificationService::class)->notifyMatchesForProfile($this->profile);

        session()->flash('status', 'Your profile has been updated successfully!');
        $this->redirect(route('profile.show'));
    }

    public function removePicture(): void
    {
        $this->remove_picture = true;
        $this->new_profile_picture = null;
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
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit Profile</h1>
            <p class="mt-1 text-zinc-600 dark:text-zinc-400">Update your transfer profile information</p>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
            </div>
        @endif

        <form wire:submit="save">
            <!-- Availability Toggle -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-zinc-900 dark:text-white">Transfer Availability</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            When disabled, you won't appear in search results and cannot send new requests.
                        </p>
                    </div>
                    <flux:switch wire:model="is_available_for_transfer" />
                </div>
            </div>

            <!-- Profile Picture -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Profile Picture</h2>

                <div class="flex items-center gap-6">
                    @if(!$remove_picture && $profile->profile_picture_url && !$new_profile_picture)
                        <img src="{{ $profile->profile_picture_url }}" alt="Profile" class="w-24 h-24 rounded-full object-cover">
                    @elseif($new_profile_picture)
                        <img src="{{ $new_profile_picture->temporaryUrl() }}" alt="New Profile" class="w-24 h-24 rounded-full object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                            <flux:icon name="user" class="w-12 h-12 text-zinc-400" />
                        </div>
                    @endif

                    <div class="flex-1">
                        <input
                            type="file"
                            wire:model="new_profile_picture"
                            accept="image/jpeg,image/png"
                            class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-zinc-700 dark:file:text-zinc-300"
                        >
                        <p class="mt-1 text-xs text-zinc-500">Max 2MB, JPG or PNG only</p>
                        @error('new_profile_picture') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        @if($profile->profile_picture_url && !$remove_picture)
                            <button type="button" wire:click="removePicture" class="mt-2 text-sm text-red-600 hover:text-red-700">
                                Remove current picture
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Personal Details -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Personal Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:input
                        wire:model="first_name"
                        label="First Name"
                        required
                    />

                    <flux:input
                        wire:model="last_name"
                        label="Last Name"
                        required
                    />

                    <flux:input
                        wire:model="employee_number"
                        label="Employee Number"
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
                </div>
            </div>

            <!-- Employment & Location -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Employment & Location</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <flux:select wire:model="employer_id" label="Current Employer" required>
                            <option value="">Select employer</option>
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
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Contact Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:input
                        wire:model="contact_number"
                        label="Contact Number"
                        required
                    />

                    <flux:input
                        wire:model="alternative_contact_number"
                        label="Alternative Contact Number"
                    />

                    <div class="md:col-span-2">
                        <flux:select wire:model="preferred_communication" label="Preferred Communication" required>
                            <option value="email">Email</option>
                            <option value="phone">Phone</option>
                            <option value="both">Both Email and Phone</option>
                        </flux:select>
                    </div>
                </div>
            </div>

            <!-- Preferred Locations -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Preferred Transfer Locations</h2>
                <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                    Select up to 3 locations where you would like to be transferred to.
                </p>

                @error('preferred_locations')
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    </div>
                @enderror

                @foreach([1, 2, 3] as $priority)
                    <div class="mb-4 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <h3 class="font-medium text-zinc-900 dark:text-white mb-3">
                            {{ $priority === 1 ? '1st' : ($priority === 2 ? '2nd' : '3rd') }} Choice
                            @if($priority === 1) <span class="text-red-500">*</span> @endif
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:select wire:model.live="preferred_locations.{{ $priority }}.region_id" label="Region">
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
            </div>

            <!-- Actions -->
            <div class="flex justify-between">
                <flux:button :href="route('profile.show')" variant="ghost" wire:navigate>
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Save Changes
                </flux:button>
            </div>
        </form>
</div>
