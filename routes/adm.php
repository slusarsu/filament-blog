<?php

use App\Adm\Controllers\CategoryController;
use App\Adm\Controllers\PageController;
use App\Adm\Controllers\PostController;
use App\Adm\Controllers\TagController;
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

Route::get('/', function () {
    return admView('index');
});

Route::get('{slug}', [PageController::class, 'page'])->name('page');
Route::get('post/{slug}', [PostController::class, 'post'])->name('post');
Route::get('tag/{slug}', [TagController::class, 'tag'])->name('tag');
Route::get('category/{slug}', [CategoryController::class, 'category'])->name('category');

