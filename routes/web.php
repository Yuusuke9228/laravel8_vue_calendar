<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index']);
Route::get('/calendars', [CalendarController::class, 'index']);
