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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('score')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->nullable();
            $table->string('id_perserta')->nullable();
            $table->string('id_juri')->nullable();
            $table->string('id_sesi')->nullable();
            $table->string('babak')->nullable();
            $table->string('partai')->nullable();
            $table->string('arena')->nullable();
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
        Schema::dropIfExists('scores');
    }
};
