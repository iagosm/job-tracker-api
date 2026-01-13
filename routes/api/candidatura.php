<?php
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\CandidaturaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->name('auth.')->group(function(){
  Route::middleware('auth:sanctum')->group(function(){
    Route::get('/candidaturas', [CandidaturaController::class, 'getAll'])->name('categorias');
  });
});