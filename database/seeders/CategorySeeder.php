<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Seminar',
                'description' => 'Kegiatan seminar akademik maupun non akademik'
            ],
            [
                'name' => 'Workshop',
                'description' => 'Pelatihan dan workshop'
            ],
            [
                'name' => 'Lomba',
                'description' => 'Kompetisi mahasiswa'
            ],
            [
                'name' => 'Webinar',
                'description' => 'Seminar online'
            ],
            [
                'name' => 'Bootcamp',
                'description' => 'Pelatihan intensif'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
