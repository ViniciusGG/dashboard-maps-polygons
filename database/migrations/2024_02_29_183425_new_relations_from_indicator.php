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
        // Drop existing foreign key constraint
        Schema::table('license_indicators', function (Blueprint $table) {
            $table->dropForeign(['indicator_id']);
        });

        // Truncate tables
        DB::table('license_indicators')->truncate();
        DB::table('indicators')->truncate();

        Schema::table('indicators', function (Blueprint $table) {
            $table->foreignId('filter_id')->nullable()->after('name')->constrained('filters');
            $table->text('description')->nullable()->after('name');
            $table->string('image')->nullable()->after('description');
        });

        // Recreate foreign key constraint
        Schema::table('license_indicators', function (Blueprint $table) {
            $table->foreign('indicator_id')->references('id')->on('indicators');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropForeign(['filter_id']);
            $table->dropColumn('filter_id');
        });
    }
};
