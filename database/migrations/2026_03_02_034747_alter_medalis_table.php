<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medalis', function (Blueprint $table) {
            $table->string('kontigen')->nullable()->after('id_peserta');
            $table->string('kelas')->nullable()->after('name');
            $table->string('kelamin')->nullable()->after('kelas');
            $table->string('kategori')->nullable()->after('kelamin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medalis', function (Blueprint $table) {
            //
        });
    }
};
