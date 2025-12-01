<?php

namespace Database\Seeders;

use App\Models\JobTitle;
use Illuminate\Database\Seeder;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobTitles = [
            // Administrative
            ['name' => 'Administrative Officer', 'abbreviation' => 'AO'],
            ['name' => 'Senior Administrative Officer', 'abbreviation' => 'SAO'],
            ['name' => 'Chief Administrative Officer', 'abbreviation' => 'CAO'],
            ['name' => 'Control Administrative Officer', 'abbreviation' => 'CtrlAO'],
            ['name' => 'Deputy Director', 'abbreviation' => 'DD'],
            ['name' => 'Director', 'abbreviation' => 'Dir'],

            // Clerical
            ['name' => 'Clerk', 'abbreviation' => null],
            ['name' => 'Senior Clerk', 'abbreviation' => null],
            ['name' => 'Chief Clerk', 'abbreviation' => null],
            ['name' => 'Registry Clerk', 'abbreviation' => null],

            // Education
            ['name' => 'Teacher', 'abbreviation' => null],
            ['name' => 'Senior Teacher', 'abbreviation' => null],
            ['name' => 'Head of Department', 'abbreviation' => 'HOD'],
            ['name' => 'Deputy Principal', 'abbreviation' => null],
            ['name' => 'Principal', 'abbreviation' => null],
            ['name' => 'Education Officer', 'abbreviation' => 'EO'],
            ['name' => 'Senior Education Officer', 'abbreviation' => 'SEO'],
            ['name' => 'Chief Education Officer', 'abbreviation' => 'CEO'],
            ['name' => 'Inspector of Education', 'abbreviation' => 'IE'],

            // Health
            ['name' => 'Enrolled Nurse', 'abbreviation' => 'EN'],
            ['name' => 'Registered Nurse', 'abbreviation' => 'RN'],
            ['name' => 'Senior Registered Nurse', 'abbreviation' => 'SRN'],
            ['name' => 'Chief Registered Nurse', 'abbreviation' => 'CRN'],
            ['name' => 'Matron', 'abbreviation' => null],
            ['name' => 'Medical Officer', 'abbreviation' => 'MO'],
            ['name' => 'Senior Medical Officer', 'abbreviation' => 'SMO'],
            ['name' => 'Chief Medical Officer', 'abbreviation' => 'CMO'],
            ['name' => 'Pharmacist', 'abbreviation' => null],
            ['name' => 'Senior Pharmacist', 'abbreviation' => null],
            ['name' => 'Radiographer', 'abbreviation' => null],
            ['name' => 'Laboratory Technician', 'abbreviation' => null],
            ['name' => 'Environmental Health Officer', 'abbreviation' => 'EHO'],

            // Finance & Accounting
            ['name' => 'Accountant', 'abbreviation' => null],
            ['name' => 'Senior Accountant', 'abbreviation' => null],
            ['name' => 'Chief Accountant', 'abbreviation' => null],
            ['name' => 'Finance Officer', 'abbreviation' => null],
            ['name' => 'Internal Auditor', 'abbreviation' => null],
            ['name' => 'Senior Internal Auditor', 'abbreviation' => null],

            // Human Resources
            ['name' => 'Human Resources Officer', 'abbreviation' => 'HRO'],
            ['name' => 'Senior Human Resources Officer', 'abbreviation' => 'SHRO'],
            ['name' => 'Chief Human Resources Officer', 'abbreviation' => 'CHRO'],
            ['name' => 'Human Resources Practitioner', 'abbreviation' => 'HRP'],

            // Information Technology
            ['name' => 'IT Technician', 'abbreviation' => null],
            ['name' => 'IT Officer', 'abbreviation' => null],
            ['name' => 'Senior IT Officer', 'abbreviation' => null],
            ['name' => 'Systems Administrator', 'abbreviation' => null],
            ['name' => 'Network Administrator', 'abbreviation' => null],

            // Law Enforcement & Security
            ['name' => 'Police Constable', 'abbreviation' => null],
            ['name' => 'Police Sergeant', 'abbreviation' => null],
            ['name' => 'Police Inspector', 'abbreviation' => null],
            ['name' => 'Chief Inspector', 'abbreviation' => null],
            ['name' => 'Correctional Officer', 'abbreviation' => null],
            ['name' => 'Senior Correctional Officer', 'abbreviation' => null],

            // Immigration & Customs
            ['name' => 'Immigration Officer', 'abbreviation' => null],
            ['name' => 'Senior Immigration Officer', 'abbreviation' => null],
            ['name' => 'Chief Immigration Officer', 'abbreviation' => null],
            ['name' => 'Customs Officer', 'abbreviation' => null],
            ['name' => 'Senior Customs Officer', 'abbreviation' => null],

            // Social Services
            ['name' => 'Social Worker', 'abbreviation' => null],
            ['name' => 'Senior Social Worker', 'abbreviation' => null],
            ['name' => 'Chief Social Worker', 'abbreviation' => null],
            ['name' => 'Community Development Officer', 'abbreviation' => null],

            // Technical & Engineering
            ['name' => 'Technician', 'abbreviation' => null],
            ['name' => 'Senior Technician', 'abbreviation' => null],
            ['name' => 'Engineer', 'abbreviation' => null],
            ['name' => 'Senior Engineer', 'abbreviation' => null],
            ['name' => 'Quantity Surveyor', 'abbreviation' => null],
            ['name' => 'Architect', 'abbreviation' => null],

            // Agriculture & Environment
            ['name' => 'Agricultural Officer', 'abbreviation' => null],
            ['name' => 'Senior Agricultural Officer', 'abbreviation' => null],
            ['name' => 'Veterinary Officer', 'abbreviation' => null],
            ['name' => 'Forestry Officer', 'abbreviation' => null],
            ['name' => 'Conservation Officer', 'abbreviation' => null],

            // Support Services
            ['name' => 'Driver', 'abbreviation' => null],
            ['name' => 'Messenger', 'abbreviation' => null],
            ['name' => 'Security Guard', 'abbreviation' => null],
            ['name' => 'Cleaner', 'abbreviation' => null],
            ['name' => 'Groundsman', 'abbreviation' => null],

            // Legal
            ['name' => 'Legal Officer', 'abbreviation' => null],
            ['name' => 'Senior Legal Officer', 'abbreviation' => null],
            ['name' => 'State Advocate', 'abbreviation' => null],
            ['name' => 'Magistrate', 'abbreviation' => null],

            // Communications & Media
            ['name' => 'Public Relations Officer', 'abbreviation' => 'PRO'],
            ['name' => 'Communications Officer', 'abbreviation' => null],
            ['name' => 'Journalist', 'abbreviation' => null],

            // Statistics & Planning
            ['name' => 'Statistician', 'abbreviation' => null],
            ['name' => 'Planner', 'abbreviation' => null],
            ['name' => 'Senior Planner', 'abbreviation' => null],
            ['name' => 'Economist', 'abbreviation' => null],

            // Library & Archives
            ['name' => 'Librarian', 'abbreviation' => null],
            ['name' => 'Senior Librarian', 'abbreviation' => null],
            ['name' => 'Archivist', 'abbreviation' => null],

            // Procurement
            ['name' => 'Procurement Officer', 'abbreviation' => null],
            ['name' => 'Senior Procurement Officer', 'abbreviation' => null],
            ['name' => 'Chief Procurement Officer', 'abbreviation' => null],
        ];

        foreach ($jobTitles as $jobTitle) {
            JobTitle::firstOrCreate(
                ['name' => $jobTitle['name']],
                [
                    'abbreviation' => $jobTitle['abbreviation'],
                    'is_active' => true,
                ]
            );
        }
    }
}
