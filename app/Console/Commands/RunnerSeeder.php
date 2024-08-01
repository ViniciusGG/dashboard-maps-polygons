<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RunnerSeeder extends Command
{

    protected $signature = 'app:runner-seeder';

    protected $description = 'Command run to generate seeder indicators,filters and services';


    public function handle()
    {
        $this->call('db:seed', [
            '--class' => 'IndicatorsSeeder',
        ]);

        $this->call('db:seed', [
            '--class' => 'FiltersSeeder',
        ]);

        $this->call('db:seed', [
            '--class' => 'ServicesSeeder',
        ]);
    }
}
