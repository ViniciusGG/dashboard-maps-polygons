<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Workspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workspace = Workspace::first();

        Alert::create([
            'workspace_id' => $workspace->id,
            'name' => 'Praia da Torre',
            'indicator' => 1,
            'status_id' => 1,
            'category' => 2,
            'satellite_id' => 1,
            'alert_datetime' => now()->subDays(3),
            'lat' => '38.690833',
            'lng' => '-9.334167',
        ]);


        Alert::create([
            'workspace_id' => $workspace->id,
            'name' => 'Forte da Torre',
            'indicator' => 2,
            'status_id' => 1,
            'category' => 1,
            'satellite_id' => 1,
            'alert_datetime' => now()->subDays(5),
            'lat' => '38.696522',
            'lng' => '-9.420128',
        ]);

        Alert::create([
            'workspace_id' => $workspace->id,
            'name' => 'Forte da Barra',
            'indicator' => 2,
            'status_id' => 1,
            'category' => 4,
            'satellite_id' => 1,
            'alert_datetime' => now()->subDays(10),
            'lat' => '38.694925',
            'lng' => '-9.458202',
        ]);

        for($i = 0; $i < 10; $i++){

            Alert::create([
                'workspace_id' => $workspace->id,
                'name' => 'Forte da Barra '. $i,
                'indicator' => $i % 2 == 0 ? 1 : 2,
                'status_id' =>  1,
                'category' => $i % 2 == 0 ? 3 : 1,
                'satellite_id' => 1,
                'alert_datetime' => now()->subDays(rand(1,50)),
                'lat' => $this->generateRandomLatitude(),
                'lng' => $this->generateRandomLongitude(),
            ]);
        }
    }

    private function generateRandomLatitude(): string
    {
        return number_format(38.6908 + (mt_rand() / mt_getrandmax()) * 0.015, 6);
    }

    private function generateRandomLongitude(): string
    {
        return number_format(-9.3334 - (mt_rand() / mt_getrandmax()) * 0.015, 6);
    }
}
