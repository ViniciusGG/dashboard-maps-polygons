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
        $description = '{"en": "Area closed to the public 30 ug/ml < Clorofile < 50 ug/ml - may possibly have the following impacts on human health: skin rash", "pt": "Área fechada ao público 30 ug/ml < Clorofila < 50 ug/ml - pode possivelmente ter os seguintes impactos na saúde humana: erupção cutânea"}';
        DB::table('satellites')->update(['description' => null]);

        Schema::table('satellites', function (Blueprint $table) {
            $table->json('description')->nullable()->change();
        });

        DB::table('satellites')->update(['description' => $description]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satellites', function (Blueprint $table) {
            $table->json('description')->nullable(false)->change();
        });
    }
};
