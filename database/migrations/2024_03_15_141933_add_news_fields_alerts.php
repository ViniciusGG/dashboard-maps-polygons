<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->foreignId('satellite_id')->nullable()->after('status_id')->constrained('satellites');
            $table->integer('intensity')->nullable()->default(0)->after('satellite_id');
            $table->integer('area')->nullable()->default(0)->after('intensity');
            $table->integer('severity')->nullable()->default(1)->after('area');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropColumn('satellite_id');
            $table->dropColumn('intensity');
            $table->dropColumn('area');
            $table->dropColumn('severity');
            $table->string('type')->nullable();
        });

    }
};
