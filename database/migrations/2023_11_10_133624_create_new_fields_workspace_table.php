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
        Schema::table('workspaces', function (Blueprint $table) {
            $table->json('region_map_area')->nullable()->after('admin_name');
            $table->json('region_map_area_pending')->nullable()->after('region_map_area');
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('pending')->after('region_map_area_pending');
            $table->string('code_azulfy')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
