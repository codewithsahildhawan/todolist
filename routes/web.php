<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Homepage
Route::get('/', [ItemController::class, 'index'])->name("todo.index");


// create todo route

Route::post('/create', [ItemController::class, 'store'])->name("todo.store");
Route::get('/fetch', [ItemController::class, 'fetch'])->name("todo.fetch");
Route::get('/destroy/{id}', [ItemController::class, 'destroy'])->name("todo.delete");
Route::get('/update-status/{id}', [ItemController::class, 'updateStatus'])->name("todo.status");

