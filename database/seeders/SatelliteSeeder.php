<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportMedia;
use App\Models\Satellite;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;


class SatelliteSeeder extends Seeder
{

    public function run(): void
    {
        $descripion = [
            'en' => 'APT at 4 lines per second - interlaced visible light and infrared images. Close area to public 30 ug/ml < Faecals < 50 ug/ml - can possibly have the following impacts of human health: skin rash',
            'pt' => 'APT a 4 linhas por segundo - imagens intercaladas de luz visível e infravermelho. Área próxima ao público 30 ug/ml < Feacais < 50 ug/ml - possivelmente pode ter os seguintes impactos na saúde humana: erupção cutânea'
        ];

        Satellite::create([
            'name' => 'Meteosat-12',
            'description' => $descripion,
        ]);
    }
}
