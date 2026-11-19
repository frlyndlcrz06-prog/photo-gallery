<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;

Route::get('/', function () {
    return redirect()->route('photos.index');
});

Route::get('/photos', [PhotoController::class, 'index'])
    ->name('photos.index');

Route::post('/photos', [PhotoController::class, 'store'])
    ->name('photos.store');

Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])
    ->name('photos.destroy');
