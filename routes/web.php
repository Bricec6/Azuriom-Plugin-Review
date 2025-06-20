<?php

use Azuriom\Plugin\Review\Controllers\ReviewController;
use Azuriom\Plugin\Review\Controllers\ReviewHomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your plugin. These
| routes are loaded by the RouteServiceProvider of your plugin within
| a group which contains the "web" middleware group and your plugin name
| as prefix. Now create something great!
|
*/

Route::get('/', [ReviewHomeController::class, 'index'])->name('index');

Route::resource('review', ReviewController::class)
    ->middleware(['auth', 'verified'])
    ->only(['store', 'destroy']);
