<?php

use Slim\App;
use Src\Controller\ProdutoController;
use Src\Controller\CompraController;
use Src\Controller\JurosController;
use Src\Controller\EstatisticaController;

return function (App $app) {
    // Produtos
    $app->post('/produtos', [ProdutoController::class, 'store']);

    // Compras
    $app->post('/compras', [CompraController::class, 'store']);
    $app->get('/compras', [CompraController::class, 'index']);

    // Juros
    $app->put('/juros', [JurosController::class, 'update']);

    // Estatísticas
    $app->get('/estatistica', [EstatisticaController::class, 'index']);
};