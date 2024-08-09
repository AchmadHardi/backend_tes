<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Periksa apakah tabel 'units' sudah ada sebelum membuatnya
        if (!Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id(); // Kolom primary key
                $table->string('name'); // Kolom untuk nama unit
                $table->timestamps(); // Kolom timestamp untuk created_at dan updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Hapus tabel 'units' jika ada
        Schema::dropIfExists('units');
    }
};
