<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $description = '{"en": "APT at 4 lines per second - interlaced visible light and infrared images. Close area to public 30 ug/ml < Faecals < 50 ug/ml - can possibly have the following impacts of human health: skin rash", "pt": "APT a 4 linhas por segundo - imagens intercaladas de luz visível e infravermelho. Área próxima ao público 30 ug/ml < Feacais < 50 ug/ml - possivelmente pode ter os seguintes impactos na saúde humana: erupção cutânea"}';

        DB::table('indicators')->update(['description' => null]);

        Schema::table('indicators', function (Blueprint $table) {
            $table->json('description')->nullable()->change();
        });

        DB::table('indicators')->update(['description' => $description]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->json('description')->nullable(false)->change();
        });
    }
};
