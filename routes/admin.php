<?php

use Azuriom\Plugin\Review\Controllers\Admin\AdminController;
use Azuriom\Plugin\Review\Controllers\Admin\ImportController;
use Azuriom\Plugin\Review\Controllers\Admin\SettingController;
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

Route::get('/', [AdminController::class, 'index'])->name('index');

Route::get('/settings', [SettingController::class, 'show'])->name('settings');
Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');

Route::get('/imports', [ImportController::class, 'show'])->name('imports');
Route::post('/imports', [ImportController::class, 'save'])->name('imports.save');
Route::post('/imports/{domain}/test', [ImportController::class, 'test'])->name('imports.test');
Route::post('/imports/{domain}/sync', [ImportController::class, 'sync'])->name('imports.sync');
