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
        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        Schema::create('subclassifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification_id')->constrained('classifications')->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('qualifications', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        Schema::create('work_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subclassification_id')->constrained('subclassifications')->onDelete('cascade');
            $table->string('kode_jabatan')->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('lsps', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        Schema::create('associations', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_positions');
        Schema::dropIfExists('subclassifications');
        Schema::dropIfExists('classifications');
        Schema::dropIfExists('qualifications');
        Schema::dropIfExists('lsps');
        Schema::dropIfExists('associations');
    }
};
