<x-mail::message>
# Transfer Request Update

Hello {{ $transferRequest->requester->first_name }},

We wanted to let you know that the employee you contacted has decided not to proceed with the transfer arrangement at this time.

Please don't be discouraged - there are many other employees looking for transfers, and new profiles are added regularly.

<x-mail::button :url="route('transfers.index')">
Find More Matches
</x-mail::button>

Keep looking - your perfect transfer match could be just around the corner!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
