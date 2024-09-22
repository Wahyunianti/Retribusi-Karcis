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
        Schema::create('karcis_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('kolektor');
            $table->date('tgl_ambil');            
            $table->unsignedBigInteger('jenis_id');
            $table->integer('jml');
            $table->integer('total');
            $table->unsignedBigInteger('area_id');
            $table->string('users_id');
            $table->string('file')->nullable();
            $table->string('nomor')->nullable();
            $table->foreign('area_id')->references('id')->on('area')->cascadeOnUpdate();   
            $table->foreign('jenis_id')->references('id')->on('jenis')->cascadeOnUpdate();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karcis_keluar');
    }
};
