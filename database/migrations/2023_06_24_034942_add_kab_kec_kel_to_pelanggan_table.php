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
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kabupaten')->nullable(true);
            $table->unsignedBigInteger('id_kecamatan')->nullable(true);
            $table->unsignedBigInteger('id_kelurahan')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn(['id_kabupaten', 'id_kecamatan', 'id_kelurahan']);
        });
    }
};
