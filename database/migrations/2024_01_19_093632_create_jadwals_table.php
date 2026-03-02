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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('perserta_biru')->nullable();
            $table->string('perserta_merah')->nullable();
            $table->string('score_merah')->nullable();
            $table->string('score_biru')->nullable();;
            $table->string('keterangan')->nullable();
            $table->string('arena')->nullable();
            $table->string('status')->nullable();
            $table->string('menang')->nullable();
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
        Schema::dropIfExists('jadwals');
    }
};
