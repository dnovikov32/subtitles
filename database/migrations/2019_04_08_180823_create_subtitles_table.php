<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtitles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('film_id')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('season')->default(0);
            $table->tinyInteger('episode')->default(0);
            $table->tinyInteger('status');
            $table->timestamps();

            $table->foreign('film_id')
                ->references('id')
                ->on('films')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('subtitles', function(Blueprint $table) {
            $table->dropForeign(['film_id']);
        });

        Schema::dropIfExists('subtitles');
    }
};
