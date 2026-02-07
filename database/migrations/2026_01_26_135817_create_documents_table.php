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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            // --- Relasi ---
            $table->foreignId('upload_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade'); // Siapa pengupload
            $table->foreignId('category_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade'); // Jenis dokumen
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->onUpdate('cascade')->onDelete('cascade'); // Tahun Anggaran berapa

            // --- Identitas Dokumen ---
            $table->string('title'); // Perihal atau Judul Dokumen
            $table->string('document_number')->nullable(); // Nomor Surat/Dokumen (Misal: 900/123/KEU/2024)
            $table->date('document_date'); // Tanggal yang tertera di surat
            $table->text('description')->nullable(); // Keterangan tambahan (bisa untuk keyword pencarian)

            // --- Integrasi Google Drive ---
            $table->string('google_drive_id')->unique(); // ID file di Google Drive
            $table->string('file_path'); 
            $table->string('file_name');
            $table->bigInteger('file_size');
            $table->string('file_type');

            // 1. Level Kerahasiaan
            // 'biasa' = semua staff sub-bagian bisa lihat
            // 'rahasia' = hanya pembuat dan Kasubbag yang bisa lihat
            $table->enum('security_level', ['public', 'internal'])->default('internal');

            $table->timestamps();
            $table->softDeletes(); // Agar kalau dihapus, tidak langsung hilang (masuk tong sampah dulu)

            // Indexing agar pencarian cepat
            $table->index('document_number');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
