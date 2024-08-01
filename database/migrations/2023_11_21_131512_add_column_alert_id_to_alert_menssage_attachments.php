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
        Schema::table('alert_message_attachments', function (Blueprint $table) {
            $table->foreignId('alert_id')->nullable()->after('file_type')->constrained('alerts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alert_menssage_attachments', function (Blueprint $table) {
            $table->dropColumn('alert_id');
        });
    }
};
