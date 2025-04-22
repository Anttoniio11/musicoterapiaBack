<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('audio_id')->constrained('audios')->onDelete('cascade');
            $table->timestamps();


        });

    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
