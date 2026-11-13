<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProdutoController;


Route::get('/ola', function () {
    return 'Olá!';
});


Route::get('/usuarios', [UsuarioController::class, 'index']);

Route::get('/produtos', [ProdutoController::class, 'index']);

Route::get('/produtos/criar', [ProdutoController::class, 'create']);

Route::get('/produtos/{id}', [ProdutoController::class, 'show']);

Route::post('/produtos', [ProdutoController::class, 'store']);

Route::put('/produtos/{id}', [ProdutoController::class, 'update']);

Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy']);