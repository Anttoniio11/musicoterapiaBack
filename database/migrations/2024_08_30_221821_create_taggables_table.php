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
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tag_id')->constrained()->onDelete('cascade'); // ID del tag
            //$table->morphs('taggable'); // Crea columnas taggable_id y taggable_type ..ahorra dos lineas
            
            $table->unsignedBigInteger('taggable_id'); // ID del modelo relacionado (Audio, Podcast, etc.)
            $table->string('taggable_type'); // Tipo del modelo relacionado

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};
