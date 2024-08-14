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
        if (!Schema::hasTable('checklists')) {
            Schema::create('checklists', function (Blueprint $table) {
                $table->id(); // unsignedBigInteger secara default
                $table->unsignedBigInteger('user_id'); // Tipe data harus sama dengan primary key di tabel users
                $table->string('title');
                $table->timestamps();

                // Menambahkan foreign key
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
