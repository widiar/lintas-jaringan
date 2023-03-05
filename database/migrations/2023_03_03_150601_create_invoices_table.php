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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('inv_number');
            $table->string('nama');
            $table->string('alamat');
            $table->string('nohp');
            $table->date('tanggal_pasang')->nullable(true);
            $table->unsignedBigInteger('user_id');
            $table->integer('harga');
            $table->integer('ppn');
            $table->bigInteger('total_harga');
            $table->string('nama_paket');
            $table->string('kecepatan');
            $table->text('fitur')->nullable(true);
            $table->string('status')->default('PENDING');
            $table->text('xendit')->nullable();
            $table->date('jatuh_tempo');
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
        Schema::dropIfExists('invoices');
    }
};
