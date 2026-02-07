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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();// Menghubungkan dengan tabel users
            // onDelete('cascade') berarti jika user dihapus, log-nya ikut terhapus (opsional)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('action'); // Contoh: 'LOGIN', 'LOGOUT', 'UPLOAD_DOKUMEN'
            $table->string('ip_address')->nullable(); // Mencatat IP User
            $table->text('user_agent')->nullable(); // Mencatat Browser/Device
            $table->text('details')->nullable(); // Keterangan tambahan (opsional)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
