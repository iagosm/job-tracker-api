<?php
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\CandidaturaController;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->name('auth.')->group(function(){
  Route::post('/register',RegisterController::class)->name('register');
  Route::post('/login',LoginController::class)->name('login');
  
  Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',LogoutController::class)->name('login');
  });
});