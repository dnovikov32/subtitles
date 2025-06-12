<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/v1'], function() {
    Route::namespace('Api\V1')->group(function () {
        Route::get('/films/index', [\App\Http\Controllers\Api\V1\FilmsController::class, 'index']);
        Route::get('/films/find/{id}', [\App\Http\Controllers\Api\V1\FilmsController::class, 'find']);
        Route::post('/films/edit', [\App\Http\Controllers\Api\V1\FilmsController::class, 'edit']);
        Route::post('/films/destroy', [\App\Http\Controllers\Api\V1\FilmsController::class, 'destroy']);

        Route::get('/serials/show/{id}', [\App\Http\Controllers\Api\V1\SerialsController::class, 'show']);

        Route::get('/subtitles/show/{id}', [\App\Http\Controllers\Api\V1\SubtitleController::class, 'show']);
        Route::get('/subtitles/find/{id}', [\App\Http\Controllers\Api\V1\SubtitleController::class, 'find']);
        Route::post('/subtitles/update', [\App\Http\Controllers\Api\V1\SubtitleController::class, 'update']);
        Route::post('/subtitles/destroy', [\App\Http\Controllers\Api\V1\SubtitleController::class, 'destroy']);
    });
});

Route::get('/{any}', [\App\Http\Controllers\Web\AppController::class, 'index'])->where('any', '.*');
