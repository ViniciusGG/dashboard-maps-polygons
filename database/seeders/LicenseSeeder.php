<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // License 1 -- Basic
        $license1 = License::create([
            'name' => 'Basic',
            'members' => 10,
            'color' => '#0000FF'
        ]);
        //insert uuid
        $license1->services()->attach([1 => ['uuid' => Str::uuid()]]);
        $license1->filters()->attach([1 => ['uuid' => Str::uuid()]]);

        $license1->indicators()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()]
        ]);


        // License 2 -- Plus
        $license2 = License::create([
            'name' => 'Plus',
            'members' => 20,
            'color' => '#FFFF00'
        ]);

        $license2->services()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()]
        ]);
        $license2->filters()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()]
        ]);
        $license2->indicators()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()],
            3 => ['uuid' => Str::uuid()],
            4 => ['uuid' => Str::uuid()],
        ]);

        // License 3 -- Premium
        $license3 = License::create([
            'name' => 'Premium',
            'members' => 30,
            'color' => '#00FF00'
        ]);

        $license3->services()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()],
            3 => ['uuid' => Str::uuid()]
        ]);
        $license3->filters()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()],
            3 => ['uuid' => Str::uuid()]
        ]);
        $license3->indicators()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()],
            3 => ['uuid' => Str::uuid()],
            4 => ['uuid' => Str::uuid()],
            5 => ['uuid' => Str::uuid()],
            6 => ['uuid' => Str::uuid()]
        ]);

        // License 4 -- Enterprise
        $license4 = License::create([
            'name' => 'Enterprise',
            'members' => 40,
            'color' => '#FF0000'
        ]);

        $license4->services()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()],
            3 => ['uuid' => Str::uuid()],
            4 => ['uuid' => Str::uuid()]
        ]);
        $license4->filters()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()],
            3 => ['uuid' => Str::uuid()]
        ]);
        $license4->indicators()->attach([
            1 => ['uuid' => Str::uuid()],
            2 => ['uuid' => Str::uuid()],
            3 => ['uuid' => Str::uuid()],
            4 => ['uuid' => Str::uuid()],
            5 => ['uuid' => Str::uuid()],
            6 => ['uuid' => Str::uuid()],
            7 => ['uuid' => Str::uuid()],
            8 => ['uuid' => Str::uuid()]
        ]);

    }
}
