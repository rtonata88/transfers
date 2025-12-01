<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Erongo', 'code' => 'ER'],
            ['name' => 'Hardap', 'code' => 'HA'],
            ['name' => 'Karas', 'code' => 'KA'],
            ['name' => 'Kavango East', 'code' => 'KE'],
            ['name' => 'Kavango West', 'code' => 'KW'],
            ['name' => 'Khomas', 'code' => 'KH'],
            ['name' => 'Kunene', 'code' => 'KU'],
            ['name' => 'Ohangwena', 'code' => 'OH'],
            ['name' => 'Omaheke', 'code' => 'OM'],
            ['name' => 'Omusati', 'code' => 'OS'],
            ['name' => 'Oshana', 'code' => 'ON'],
            ['name' => 'Oshikoto', 'code' => 'OT'],
            ['name' => 'Otjozondjupa', 'code' => 'OJ'],
            ['name' => 'Zambezi', 'code' => 'ZA'],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
