<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group(['middleware' => 'auth'], function(){
    Route::get('select_category',[\App\Http\Controllers\GameController::class,'index'])->name('select_category');
    Route::get('start_game/{category_id}',[\App\Http\Controllers\GameController::class,'startGame'])->name('start_game');
    Route::post('character_check',[\App\Http\Controllers\GameController::class,'characterCheck'])->name('checkIfCharExists');
    Route::get('view/statistic',[\App\Http\Controllers\GameController::class,'viewStatistics'])->name('statistic');
    Route::post('check_whole_word',[\App\Http\Controllers\GameController::class,'checkWholeWord'])->name('checkWholeWord');
});
