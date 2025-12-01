<x-mail::message>
# New Transfer Match Found!

Hello {{ $recipientProfile->first_name }},

Great news! We found a potential transfer match for you.

**{{ $matchedProfile->display_name }}** is currently located in **{{ $matchedProfile->current_location }}** and wants to transfer to your area.

**Match Details:**
- **Current Location:** {{ $matchedProfile->current_location }}
- **Employer:** {{ $matchedProfile->employer->name }}
@if($matchedProfile->jobTitle)
- **Job Title:** {{ $matchedProfile->jobTitle->name }}
@endif
@if($matchedProfile->job_grade)
- **Job Grade:** Grade {{ $matchedProfile->job_grade }}
@endif

This could be a great opportunity for a mutual transfer!

<x-mail::button :url="route('transfers.show', $matchedProfile)">
View Profile
</x-mail::button>

If you're interested, you can view their profile and send a transfer request to start the process.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
