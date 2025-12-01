<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Town;
use Illuminate\Database\Seeder;

class TownSeeder extends Seeder
{
    public function run(): void
    {
        $towns = [
            'Erongo' => [
                'Walvis Bay', 'Swakopmund', 'Henties Bay', 'Omaruru', 'Karibib',
                'Usakos', 'Arandis',
            ],
            'Hardap' => [
                'Mariental', 'Rehoboth', 'Aranos', 'Maltahohe', 'Gibeon',
                'Stampriet', 'Kalkrand',
            ],
            'Karas' => [
                'Keetmanshoop', 'Luderitz', 'Oranjemund', 'Karasburg', 'Bethanie',
                'Aus', 'Rosh Pinah', 'Aroab',
            ],
            'Kavango East' => [
                'Rundu', 'Divundu', 'Andara', 'Nyangana',
            ],
            'Kavango West' => [
                'Nkurenkuru', 'Mpungu', 'Kahenge',
            ],
            'Khomas' => [
                'Windhoek', 'Dordabis', 'Hosea Kutako',
            ],
            'Kunene' => [
                'Opuwo', 'Outjo', 'Khorixas', 'Kamanjab', 'Sesfontein',
            ],
            'Ohangwena' => [
                'Eenhana', 'Helao Nafidi', 'Okongo', 'Engela', 'Ohangwena',
                'Ondobe', 'Epembe', 'Ongenga',
            ],
            'Omaheke' => [
                'Gobabis', 'Otjinene', 'Witvlei', 'Aminuis', 'Epukiro',
            ],
            'Omusati' => [
                'Outapi', 'Oshikuku', 'Okahao', 'Ruacana', 'Tsandi',
                'Onesi', 'Ogongo', 'Okalongo',
            ],
            'Oshana' => [
                'Oshakati', 'Ondangwa', 'Ongwediva', 'Okatana', 'Ompundja',
            ],
            'Oshikoto' => [
                'Tsumeb', 'Omuthiya', 'Oniipa', 'Olukonda', 'Onankali',
                'Onandjokwe', 'Tsintsabis',
            ],
            'Otjozondjupa' => [
                'Otjiwarongo', 'Grootfontein', 'Okahandja', 'Okakarara', 'Otavi',
                'Tsumkwe', 'Kalkfeld',
            ],
            'Zambezi' => [
                'Katima Mulilo', 'Bukalo', 'Chinchimane', 'Ngoma', 'Kongola',
                'Sangwali', 'Sibbinda', 'Linyanti',
            ],
        ];

        foreach ($towns as $regionName => $townNames) {
            $region = Region::where('name', $regionName)->first();
            if ($region) {
                foreach ($townNames as $townName) {
                    Town::create([
                        'region_id' => $region->id,
                        'name' => $townName,
                    ]);
                }
            }
        }
    }
}
