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
        Schema::create('persertas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('id_pesilat')->nullable();
            $table->string('id_kontigen')->nullable();
            $table->string('gender')->nullable();
            $table->string('usia_category')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->string('category')->nullable();
            $table->string('kelas')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persertas');
    }
};
