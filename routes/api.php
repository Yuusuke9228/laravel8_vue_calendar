<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalendarController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'calendars', 'as' => 'calendars.'], function () {
    Route::get("/{id}", [CalendarController::class, 'show'])->name('show');
    Route::post('/', [CalendarController::class, 'create'])->name('create');
    Route::put('/{id}', [CalendarController::class, 'save'])->name('update');
    Route::delete('/{id}', [CalendarController::class, 'destroy'])->name('delete');
});

Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
    Route::get("/{id}", [EventController::class, 'show'])->name('show');
    Route::post('/', [EventController::class, 'create'])->name('create');
    Route::put('/{id}', [EventController::class, 'save'])->name('update');
    Route::delete('/{id}', [EventController::class, 'destroy'])->name('delete');
});


