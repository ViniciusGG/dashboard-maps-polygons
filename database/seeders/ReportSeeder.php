<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportMedia;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;


class ReportSeeder extends Seeder
{

    public function run(): void
    {
        Report::create([
            'description' => 'Erro na visualização no mapa',
            'user_id' => 1,
        ]);

        ReportMedia::create([
            'url' => 'https://i.imgur.com/BG85M46.png',
            'type' => 'image',
            'order' => 1,
            'user_id' => 1,
            'report_id' => 1,
        ]);

        ReportMedia::create([
            'url' => 'https://i.imgur.com/MWBb8UL.png',
            'type' => 'image',
            'order' => 2,
            'user_id' => 1,
            'report_id' => 1,
        ]);

        ReportMedia::create([
            'url' => 'https://imgur.com/gallery/nSoouIk',
            'type' => 'video',
            'order' => 3,
            'user_id' => 1,
            'report_id' => 1,
        ]);
    }
}


