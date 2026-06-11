<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('klasifikasi');
            $table->string('subklasifikasi');
            $table->string('kualifikasi');
            $table->string('kode_jabatan');
            $table->string('jabatan_kerja');
            $table->string('nomor_registrasi')->unique();
            $table->string('nama_lsp');
            $table->string('nama_asosiasi');
            $table->date('tanggal_ditetapkan');
            $table->date('tanggal_berlaku');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
