<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_groups', function (Blueprint $table) {
            $table->id();
            $table->string('id_sesi')->nullable();
            $table->string('id_poll')->nullable();
            $table->string('kelas')->nullable();
            $table->string('partai')->nullable();
            $table->string('merah')->nullable();
            $table->string('biru')->nullable();
            $table->string('score_merah')->nullable();
            $table->string('score_biru')->nullable();
            $table->string('deviasi_merah')->nullable();
            $table->string('deviasi_biru')->nullable();
            $table->string('timer_merah')->nullable();
            $table->string('timer_biru')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('arena')->nullable();
            $table->string('status')->nullable();
            $table->string('tipe')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('pemenang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_groups');
    }
};
