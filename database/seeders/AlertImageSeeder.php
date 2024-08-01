<?php

namespace Database\Seeders;

use App\Models\AlertImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlertImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json1 = [
            'bl' => [-8.992309570312502, 37.935533061836445],
            'br' => [-7.767333984375001, 37.935533061836445],
            'tl' => [-7.767333984375001, 36.96744946416934],
            'tr' => [-8.992309570312502, 36.96744946416934]
        ];

        AlertImage::create([
            'alert_id' => 1,
            'url' => 'https://azulfy.buzzvel.work/map-raster-filter-1.png',
            'geo_coordinates' => $json1
        ]);

        $json2 = [
            'bl' => [-10.106435546875002, 39.743098286948275],
            'br' => [-8.895192871093752, 39.743098286948275],
            'tl' => [-8.895192871093752, 38.76050866911151],
            'tr' => [-10.106435546875002, 38.76050866911151]
        ];
        AlertImage::create([
            'alert_id' => 1,
            'url' => 'https://azulfy.buzzvel.work/map-raster-filter-2.png',
            'geo_coordinates' => $json2,
        ]);
    }
}