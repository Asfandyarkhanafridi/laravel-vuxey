<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\SettingController;

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
    return view('auth/login');
});

Auth::routes();

Route::middleware('auth')->group(function () {

//Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

//Setting
    Route::resource('settings', SettingController::class)->only('edit','update');

//Users
    Route::resource('users', UserController::class);

//Profile
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

//AJAX Request
    Route::post('ajax/{method}', [AjaxController::class, 'handle'])->name('ajax.handle');

});
