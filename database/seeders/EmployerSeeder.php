<?php

namespace Database\Seeders;

use App\Models\Employer;
use Illuminate\Database\Seeder;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        $employers = [
            ['name' => 'Ministry of Education, Arts and Culture', 'abbreviation' => 'MoEAC'],
            ['name' => 'Ministry of Health and Social Services', 'abbreviation' => 'MoHSS'],
            ['name' => 'Ministry of Finance', 'abbreviation' => 'MoF'],
            ['name' => 'Ministry of Home Affairs, Immigration, Safety and Security', 'abbreviation' => 'MHAISS'],
            ['name' => 'Ministry of Works and Transport', 'abbreviation' => 'MWT'],
            ['name' => 'Ministry of Agriculture, Water and Land Reform', 'abbreviation' => 'MAWLR'],
            ['name' => 'Ministry of Justice', 'abbreviation' => 'MoJ'],
            ['name' => 'Ministry of Labour, Industrial Relations and Employment Creation', 'abbreviation' => 'MLIREC'],
            ['name' => 'Ministry of Environment, Forestry and Tourism', 'abbreviation' => 'MEFT'],
            ['name' => 'Ministry of Gender Equality, Poverty Eradication and Social Welfare', 'abbreviation' => 'MGEPESW'],
            ['name' => 'Ministry of Urban and Rural Development', 'abbreviation' => 'MURD'],
            ['name' => 'Ministry of Industrialisation and Trade', 'abbreviation' => 'MIT'],
            ['name' => 'Ministry of Mines and Energy', 'abbreviation' => 'MME'],
            ['name' => 'Ministry of Information and Communication Technology', 'abbreviation' => 'MICT'],
            ['name' => 'Ministry of Sport, Youth and National Service', 'abbreviation' => 'MSYNS'],
            ['name' => 'Ministry of Higher Education, Technology and Innovation', 'abbreviation' => 'MHETI'],
            ['name' => 'Ministry of International Relations and Cooperation', 'abbreviation' => 'MIRCO'],
            ['name' => 'Ministry of Defence and Veterans Affairs', 'abbreviation' => 'MDVA'],
            ['name' => 'Ministry of Public Enterprises', 'abbreviation' => 'MPE'],
            ['name' => 'Office of the Prime Minister', 'abbreviation' => 'OPM'],
            ['name' => 'Office of the President', 'abbreviation' => 'OP'],
            ['name' => 'Office of the Judiciary', 'abbreviation' => 'OoJ'],
            ['name' => 'Office of the Auditor General', 'abbreviation' => 'OAG'],
            ['name' => 'Office of the Ombudsman', 'abbreviation' => 'OO'],
            ['name' => 'Electoral Commission of Namibia', 'abbreviation' => 'ECN'],
            ['name' => 'Anti-Corruption Commission', 'abbreviation' => 'ACC'],
            ['name' => 'Namibia Police Force', 'abbreviation' => 'NAMPOL'],
            ['name' => 'Namibia Correctional Service', 'abbreviation' => 'NCS'],
            ['name' => 'Namibia Defence Force', 'abbreviation' => 'NDF'],
            ['name' => 'National Assembly', 'abbreviation' => 'NA'],
            ['name' => 'National Council', 'abbreviation' => 'NC'],
            ['name' => 'Other Government Agency', 'abbreviation' => 'Other'],
        ];

        foreach ($employers as $employer) {
            Employer::create($employer);
        }
    }
}
