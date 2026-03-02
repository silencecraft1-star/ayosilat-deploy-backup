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
        Schema::create('pending_tandings', function (Blueprint $table) {
            $table->id();
            $table->string('score')->nullable();
            $table->string('id_sesi')->nullable();
            $table->string('partai')->nullable();
            $table->string('juri1')->nullable();
            $table->string('juri2')->nullable();
            $table->string('juri3')->nullable();
            $table->string('id_perserta')->nullable();
            $table->string('babak')->nullable();
            $table->string('arena')->nullable();
            $table->string('status')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('isValid')->nullable();
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
        Schema::dropIfExists('pending_tandings');
    }
};
