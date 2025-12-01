<x-mail::message>
# Welcome to the Employee Transfer Portal!

Hello {{ $user->name }},

Thank you for verifying your email and joining the Employee Transfer Portal. We're excited to help you find your ideal transfer partner.

## Getting Started

1. **Complete Your Profile** - Add your employment details and preferred transfer locations
2. **Browse Matches** - See employees who match your transfer preferences
3. **Send Requests** - Connect with potential transfer partners
4. **Coordinate** - Once accepted, exchange contact details and arrange your transfer

<x-mail::button :url="route('dashboard')">
Complete Your Profile
</x-mail::button>

## Tips for Success

- Keep your profile up to date with accurate information
- Add multiple preferred locations to increase your chances of finding matches
- Write a friendly message when sending transfer requests
- Respond promptly to incoming requests

If you have any questions, please check our FAQ page or contact support.

Welcome aboard!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
