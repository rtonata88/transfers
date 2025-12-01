<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Who is eligible to use this transfer portal?',
                'answer' => 'The portal is available to all Namibian government employees who have completed their probation period and are in good standing. Employees who are currently on probation, sick leave without clearance, in rehabilitation, or under investigation are not eligible to create a profile or arrange transfers.',
                'order' => 1,
            ],
            [
                'question' => 'How does the matching system work?',
                'answer' => 'The system matches employees based on their current location and preferred transfer destinations. If you are in Town A and want to move to Town B, the system will show you employees in Town B who want to move to Town A (or your region). This creates opportunities for mutual transfers where both parties benefit.',
                'order' => 2,
            ],
            [
                'question' => 'Is my personal information protected?',
                'answer' => 'Yes, your privacy is important to us. Your contact details (phone numbers and email) are only revealed to other employees after they send you a transfer request AND you accept it. Until then, other users can only see your name (first name and last initial), current location, employer, and job grade.',
                'order' => 3,
            ],
            [
                'question' => 'What happens after I find a match?',
                'answer' => 'Once you find a potential transfer partner, you can send them a transfer request through the portal. If they accept, you will both receive each other\'s contact details. From there, you are responsible for coordinating the transfer process with your respective supervisors and HR departments.',
                'order' => 4,
            ],
            [
                'question' => 'Can I opt out of being visible in search results?',
                'answer' => 'Yes, you can toggle your availability status at any time from your profile settings. When you opt out, your profile will be hidden from all search results and you will not be able to send new transfer requests. However, you can still view and respond to any pending incoming requests.',
                'order' => 5,
            ],
            [
                'question' => 'How many transfer requests can I send?',
                'answer' => 'To maintain quality interactions, you can have a maximum of 5 pending outgoing requests at any time. Once a request is accepted, declined, or cancelled, you can send new requests.',
                'order' => 6,
            ],
            [
                'question' => 'Does using this portal guarantee a transfer?',
                'answer' => 'No, this portal only facilitates connections between employees who wish to transfer. The actual transfer process, including approvals, must still go through official government channels and your respective ministries\' HR procedures.',
                'order' => 7,
            ],
            [
                'question' => 'How do I update my preferred transfer locations?',
                'answer' => 'You can update your preferred locations at any time by editing your profile. Go to your profile page and click "Edit Profile". You can select up to 3 preferred locations in priority order.',
                'order' => 8,
            ],
            [
                'question' => 'What if I cannot find any matches?',
                'answer' => 'If there are no immediate matches, don\'t worry. New employees register regularly. Keep your profile active and check back periodically. You can also use the search function to browse all available transfer candidates, not just perfect matches.',
                'order' => 9,
            ],
            [
                'question' => 'How do I contact support?',
                'answer' => 'For technical issues or questions about the portal, please email support@transferportal.gov.na. For questions about the transfer process itself, please contact your ministry\'s HR department.',
                'order' => 10,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
