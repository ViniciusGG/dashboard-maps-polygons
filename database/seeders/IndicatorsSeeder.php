<?php

namespace Database\Seeders;

use App\Models\Indicator;
use Illuminate\Database\Seeder;


class IndicatorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $description = [
            'en' => 'Area closed to the public 30 ug/ml < Clorofile < 50 ug/ml - may possibly have the following impacts on human health: skin rash',
            'pt' => 'Área fechada ao público 30 ug/ml < Clorofila < 50 ug/ml - pode possivelmente ter os seguintes impactos na saúde humana: erupção cutânea'
        ];

        Indicator::create([
            'name' => 'Cyanobacteria (Cya)',
            'filter_id' => 1,
            'description' => $description,
            'image' => 'Cyanobacteria.png'
        ]);

        Indicator::create([
            'name' => 'Clorofile (Chl A)',
            'filter_id' => 1,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'Turbidity',
            'filter_id' => 1,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'Coloured Dissolved Organic Matter',
            'filter_id' => 1,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'Dissolved Organic Carbon',
            'filter_id' => 1,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'Water Colouring',
            'filter_id' => 1,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'Oil Spill Index',
            'filter_id' => 1,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'NDVI (normalized difference vegetation index)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'ARI (anthocyanin reflectance index)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'EVI (enhanced vegetation index)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'MSI (moisture index)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'NDMI (normalized difference moisture index)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'NDWI (normalized difference water index)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'LAI (Leaf Area Index)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'FAPAR (the fraction of absorbed photosynthetically active radiation)',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'Vegetation productivity indicator',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'Oil Spill Index',
            'filter_id' => 2,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'PM2.5',
            'filter_id' => 3,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'PM10',
            'filter_id' => 3,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'NOX',
            'filter_id' => 3,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);

        Indicator::create([
            'name' => 'COx',
            'filter_id' => 3,
            'description' => $description,
            'image' => 'faecals.jpeg'
        ]);
    }
}
