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
        Schema::create('alert_message_attachments', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('alert_message_id')->constrained();
            $table->foreignId('workspace_id')->constrained();
            $table->text('file_name'); 
            $table->text('file_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_message_attachments');
    }
};
