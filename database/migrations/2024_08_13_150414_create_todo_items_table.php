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
        Schema::create('todo_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checklist_id');
            $table->string('item');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->foreign('checklist_id')->references('id')->on('checklists')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_items');
    }
};
