<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('audios', function (Blueprint $table) {
            $table->string('image_public_id')->nullable()->after('image_file');
            $table->string('audio_public_id')->after('audio_file');
        });
    }

    public function down()
    {
        Schema::table('audios', function (Blueprint $table) {
            $table->dropColumn(['image_public_id', 'audio_public_id']);
        });
    }
};
