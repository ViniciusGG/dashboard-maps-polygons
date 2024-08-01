<?php

namespace Database\Seeders;

use App\Models\Filter;
use Illuminate\Database\Seeder;


class FiltersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Filter::create([
            'name' => 'Water',
        ]);

        Filter::create([
            'name' => 'Air',
        ]);

        Filter::create([
            'name' => 'Soil',
        ]);

    }
}


