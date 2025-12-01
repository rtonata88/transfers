<x-mail::message>
# New Transfer Request

Hello {{ $transferRequest->requestee->first_name }},

You have received a new transfer request from **{{ $transferRequest->requester->display_name }}**.

**Details:**
- **Current Location:** {{ $transferRequest->requester->current_location }}
- **Employer:** {{ $transferRequest->requester->employer->name }}
@if($transferRequest->requester->jobTitle)
- **Job Title:** {{ $transferRequest->requester->jobTitle->name }}
@endif
@if($transferRequest->requester->job_grade)
- **Job Grade:** Grade {{ $transferRequest->requester->job_grade }}
@endif

@if($transferRequest->message)
**Message from {{ $transferRequest->requester->first_name }}:**
> {{ $transferRequest->message }}
@endif

<x-mail::button :url="route('requests.incoming')">
View Request
</x-mail::button>

If you accept this request, you will both be able to see each other's contact details to coordinate your transfer.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
