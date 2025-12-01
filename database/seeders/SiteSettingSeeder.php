<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Employee Transfer Portal',
            ],
            [
                'key' => 'site_description',
                'value' => 'Connect with fellow government employees to arrange mutual transfers across Namibia.',
            ],
            [
                'key' => 'about_content',
                'value' => '<h2>About the Employee Transfer Portal</h2>
<p>The Employee Transfer Portal is an initiative designed to help Namibian government employees find and arrange mutual transfers with colleagues in different locations across the country.</p>
<h3>Our Mission</h3>
<p>We aim to simplify the process of finding transfer partners by connecting employees who have complementary relocation needs. Whether you need to move closer to family, seek new opportunities, or simply desire a change of scenery, our platform helps you find colleagues who share your goals.</p>
<h3>How It Works</h3>
<p>The portal uses a smart matching system that connects employees based on their current locations and preferred destinations. When you find a potential match, you can send them a transfer request. If they accept, you exchange contact details and can coordinate the formal transfer process through your respective HR departments.</p>
<h3>Contact Us</h3>
<p>For technical support, please email: support@transferportal.gov.na</p>
<p>For transfer policy questions, please contact your ministry\'s HR department.</p>',
            ],
            [
                'key' => 'support_email',
                'value' => 'support@transferportal.gov.na',
            ],
            [
                'key' => 'max_pending_requests',
                'value' => '5',
            ],
            [
                'key' => 'request_expiry_days',
                'value' => '30',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
