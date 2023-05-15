<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
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
Route::view('/welcome', 'welcome')->name('welcome');
Route::view('/test', 'test')->name('test');

Route::get('/', function () {
    return admView('index');
});

Route::get('{slug}', [PageController::class, 'page'])->name('page');
Route::get('post/{slug}', [PostController::class, 'post'])->name('post');
Route::get('blog', [PostController::class, 'post'])->name('post');

