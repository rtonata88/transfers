<x-mail::message>
# Great News!

Hello {{ $transferRequest->requester->first_name }},

**{{ $transferRequest->requestee->first_name }}** has accepted your transfer request!

You can now contact each other directly to coordinate your transfer arrangements.

## Contact Details

**Name:** {{ $transferRequest->requestee->full_name }}

**Email:** {{ $transferRequest->requestee->user->email }}

**Phone:** {{ $transferRequest->requestee->contact_number }}

@if($transferRequest->requestee->alternative_contact_number)
**Alternative Phone:** {{ $transferRequest->requestee->alternative_contact_number }}
@endif

**Current Location:** {{ $transferRequest->requestee->current_location }}

**Employer:** {{ $transferRequest->requestee->employer->name }}

@if($transferRequest->requestee->jobTitle)
**Job Title:** {{ $transferRequest->requestee->jobTitle->name }}
@endif

@if($transferRequest->requestee->job_grade)
**Job Grade:** Grade {{ $transferRequest->requestee->job_grade }}
@endif

<x-mail::button :url="route('requests.outgoing')">
View Request Details
</x-mail::button>

## Next Steps

1. Contact {{ $transferRequest->requestee->first_name }} to discuss the transfer details
2. Coordinate with your respective HR departments
3. Follow your ministry's official transfer procedures

Good luck with your transfer!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
