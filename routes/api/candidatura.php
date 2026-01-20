<?php
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\CandidaturaController;
use App\Http\Controllers\PlataformaController;
use App\Http\Controllers\TecnologiaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->name('auth.')->group(function(){
  Route::middleware('auth:sanctum')->group(function(){
    Route::get('/candidaturas', [CandidaturaController::class, 'getAll'])->name('candidatura');
    Route::post('/candidatura', [CandidaturaController::class, 'create'])->name('candidatura.create');
    Route::put('/candidatura/{id}', [CandidaturaController::class, 'update'])->name('candidatura.update');
    // TODO criar um para o patch // Route::put('/candidatura/{id}', [CandidaturaController::class, 'update'])->name('candidatura.update');
    Route::delete('/candidatura/{id}', [CandidaturaController::class, 'delete'])->name('candidatura.delete');

    Route::get('/tecnologias', [TecnologiaController::class, 'getAll'])->name('tecnologia');
    Route::post('/tecnologia', [TecnologiaController::class, 'create'])->name('tecnologia.create');
    Route::put('/tecnologia/{id}', [TecnologiaController::class, 'update'])->name('tecnologia.update');
    Route::delete('/tecnologia/{id}', [TecnologiaController::class, 'delete'])->name('tecnologia.delete');

    Route::get('/plataformas', [PlataformaController::class, 'getAll'])->name('plataforma');
    Route::post('/plataforma', [PlataformaController::class, 'create'])->name('plataforma.create');
    Route::put('/plataforma/{id}', [PlataformaController::class, 'update'])->name('plataforma.update');
    Route::delete('/plataforma/{id}', [PlataformaController::class, 'delete'])->name('plataforma.delete');
  });
});