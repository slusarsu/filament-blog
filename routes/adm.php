<?php

use App\Adm\Controllers\CategoryController;
use App\Adm\Controllers\PageController;
use App\Adm\Controllers\PostController;
use App\Adm\Controllers\TagController;
use App\Adm\Controllers\TranslateController;
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
Route::get('/set-locale/{lang}', [TranslateController::class, 'setLocale'])->name('set-locale');
Route::post('/switch-locale', [TranslateController::class, 'localeSwitcher'])->name('switch-locale');

Route::redirect('/', '/'.admDefaultLanguage());

Route::prefix('{lang}')
    ->middleware('translate')
    ->group(function() {

    Route::get('/', function () {
        return admView('index');
    })->name('home');

    Route::get('{slug}', [PageController::class, 'page'])->name('page');
    Route::get('post/{slug}', [PostController::class, 'post'])->name('post');
    Route::get('tag/{slug}', [TagController::class, 'tag'])->name('tag');
    Route::get('category/{slug}', [CategoryController::class, 'category'])->name('category');
});

