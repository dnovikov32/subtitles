<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subtitle_id')->nullable();
            $table->string('text1');
            $table->string('text2');
            $table->decimal('start', 12, 3)->nullable();
            $table->decimal('end', 12, 3)->nullable();
            $table->smallInteger('position');

            $table->foreign('subtitle_id')
                ->references('id')
                ->on('subtitles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('rows', function(Blueprint $table) {
            $table->dropForeign(['subtitle_id']);
        });

        Schema::dropIfExists('rows');
    }
};
