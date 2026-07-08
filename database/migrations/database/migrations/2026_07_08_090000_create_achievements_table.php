<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['prestasi', 'sertifikat'])->default('sertifikat');
            $table->string('title');
            $table->string('issuer')->nullable();     // penyelenggara / penerbit
            $table->text('description')->nullable();
            $table->date('achieved_at')->nullable();  // tanggal diperoleh
            $table->string('image_path')->nullable(); // thumbnail/badge/scan sertifikat
            $table->string('file_path')->nullable();  // dokumen resmi (PDF) opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};