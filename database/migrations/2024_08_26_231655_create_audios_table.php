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
        Schema::create('audios', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_file')->nullable(); 
            $table->string('audio_file')->unique();  
            $table->integer('duration')->comment('seconds');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
            $table->foreignId('album_id')->nullable()->constrained('albums')->onDelete('cascade');      
            $table->boolean('es_binaural')->default(false); // Indica si es un sonido binaural
            $table->float('frecuencia')->nullable(); // Frecuencia específica para sonidos binaurales
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audios');
    }
};
