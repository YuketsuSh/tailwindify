<?php

use Azuriom\Plugin\Tailwindify\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'index'])->name('index');
Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
Route::post('/clear-logs', [AdminController::class, 'clearLogs'])->name('clear-logs');
Route::get('/bootstrap', [AdminController::class, 'bootstrap'])->name('bootstrap');
Route::post('/force-compile', [AdminController::class, 'forceCompile'])->name('force-compile');

Route::post('/bootstrap/scan', [AdminController::class, 'scanBootstrapClasses'])->name('bootstrap.scan');

Route::post('/bootstrap/replacements', [AdminController::class, 'storeReplacement'])->name('bootstrap.store');
Route::put('/bootstrap/replacements/{id}', [AdminController::class, 'updateReplacement'])->name('bootstrap.update');
Route::delete('/bootstrap/replacements/{id}', [AdminController::class, 'deleteReplacement'])->name('bootstrap.delete');
