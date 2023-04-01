<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Sets
Route::get('/sets', [\App\Http\Controllers\SetsController::class, 'sets'])->name('sets');
Route::post('/set/add', [\App\Http\Controllers\SetsController::class, 'add'])->name('set.add');
Route::post('/set/{set_id}/delete', [\App\Http\Controllers\SetsController::class, 'delete'])->name('set.delete');

// recipe
Route::get('/recipe/{set_id}', [\App\Http\Controllers\RecipesController::class, 'recipes'])->name('recipe');
Route::post('/recipe/{set_id}/add', [\App\Http\Controllers\RecipesController::class, 'add'])->name('recipe.add');
Route::post('/recipe/{set_id}/delete', [\App\Http\Controllers\RecipesController::class, 'delete'])->name('recipe.delete');
Route::post('/recipe/{set_id}/update', [\App\Http\Controllers\RecipesController::class, 'update'])->name('recipe.update');

