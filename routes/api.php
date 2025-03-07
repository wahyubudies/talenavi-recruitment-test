<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('todos')
    ->controller(\App\Http\Controllers\Api\TodoController::class)
    ->group(function () {
    Route::post('/create', 'store');
    Route::get('/export', 'exportExcel');
    Route::get('/chart', 'getChart');
});
