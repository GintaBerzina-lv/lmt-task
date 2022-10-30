<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\PostController;

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

Route::get('/', function () {
    return redirect('posts/list');
});

Route::prefix('posts')->group(function () {
    Route::get('list', [PostController::class, 'list'])
        ->name('posts.list');
    Route::get('view/{post}', [PostController::class, 'view'])
        ->name('posts.view');
    Route::middleware('auth')->group(function() {
        Route::get('view', [PostController::class, 'viewnew'])
            ->name('posts.viewnew');
        Route::post('view', [PostController::class, 'edit'])
            ->name('posts.edit');
        Route::post('react/{post}', [PostController::class, 'react'])
            ->name('posts.react');
        Route::delete('view/{post}', [PostController::class, 'delete'])
            ->name('posts.delete');
    });
});

require __DIR__.'/auth.php';
