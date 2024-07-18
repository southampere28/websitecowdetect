<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuController;
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
    return view('landingpage');
});

Route::get('/menu', function() {
    return view('menu');
})->name('frontend.menu');

Route::post('/file/upload/breed', [MenuController::class, 'store'])->name('backend.storebreed');