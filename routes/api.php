<?php

use Illuminate\Support\Facades\Route;
use NovaFileShelf\Http\Controllers\FileController;
use NovaFileShelf\Http\Controllers\ShelfController;

Route::get('shelves/{shelf}', [ShelfController::class, 'show']);
Route::post('shelves/{shelf}', [ShelfController::class, 'update']);

Route::get('files/{shelf}', [FileController::class, 'show']);
