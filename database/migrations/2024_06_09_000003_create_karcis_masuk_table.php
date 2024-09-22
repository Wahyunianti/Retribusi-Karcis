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
        Schema::create('karcis_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('penyetok');
            $table->date('tgl_masuk');            
            $table->unsignedBigInteger('jenis_id');
            $table->integer('jml');
            $table->integer('total');
            $table->string('users_id');
            $table->string('file')->nullable();
            $table->string('nomor')->nullable();
            $table->foreign('jenis_id')->references('id')->on('jenis')->cascadeOnUpdate();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karcis_masuk');
    }
};
