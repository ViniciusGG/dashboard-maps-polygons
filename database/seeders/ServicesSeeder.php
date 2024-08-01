<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Database\Seeder;


class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Services::create([
            'name' => 'Space',
        ]);

        Services::create([
            'name' => 'Video',
        ]);

        Services::create([
            'name' => 'Drones',
        ]);

        Services::create([
            'name' => 'Lab Chemical Test',
        ]);        
    
    }
}


