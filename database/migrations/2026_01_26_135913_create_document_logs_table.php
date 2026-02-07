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
        Schema::create('document_logs', function (Blueprint $table) {
            $table->id();
            // Siapa?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Dokumen apa?
            $table->foreignId('document_id')->constrained('documents');

            // Ngapain? (ENUM: 'view', 'download')
            $table->string('action');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_logs');
    }
};
