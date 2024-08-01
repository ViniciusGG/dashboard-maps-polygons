<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => ['pt' => 'Novo', 'en' => 'New'], 'type' => 'new'],
            ['name' => ['pt' => 'Alerta Análise por Admin', 'en' => 'Alert Analysis by Admin'], 'type' => 'analysis'],
            ['name' => ['pt' => 'Adicionando Cada Membro da Equipa', 'en' => 'Adding Each Team Member'], 'type' => 'addMember'],
            ['name' => ['pt' => 'Análise de Parâmetros de Satélite', 'en' => 'Analysis of Satellite Parameters'], 'type' => 'analysisSatellite'],
            ['name' => ['pt' => 'Testes Iniciais de Verificação In-Situ: Análise de Resultados', 'en' => 'In-Situ Initial Verification Tests: Analysis of Results'], 'type' => 'initialAnalysisResults'],
            ['name' => ['pt' => 'Definir Ação Corretiva', 'en' => 'Define Corrective Action'], 'type' => 'defineAction'],
            ['name' => ['pt' => 'Ação Corretiva Concluída', 'en' => 'Corrective Action Completed'], 'type' => 'defineCompleted'],
            ['name' => ['pt' => 'Testes Finais de Verificação In-Situ: Análise de Resultados', 'en' => 'In-Situ Final Verification Tests: Analysis of Results'], 'type' => 'FinalAnalysisResults'],
            ['name' => ['pt' => 'Reabertura da área afetada', 'en' => 'Reopening of affected area'], 'type' => 'affectedArea'],
            ['name' => ['pt' => 'Nota/Relatório Final do Líder da Equipa', 'en' => "Team Leader's Final Note/Report "], 'type' => 'finalReport'],
            ['name' => ['pt' => 'Fechamento de Alerta', 'en' => 'Alert Closure'], 'type' => 'closed'],
        ];

        foreach ($statuses as $status) {
            \App\Models\Status::create($status);
        }
        $status = \App\Models\Status::first();

        $alerts = \App\Models\Alert::all();
        foreach ($alerts as $alert) {
            $alert->status()->associate($status);
            $alert->save();
        }

    }
}
