<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Src\Config\Database;
use Src\DAO\ProdutoDAO;
use Src\DAO\CompraDAO;
use Src\DAO\JurosDAO;
use Src\DAO\EstatisticaDAO;
use Src\Controller\ProdutoController;
use Src\Controller\CompraController;
use Src\Controller\JurosController;
use Src\Controller\EstatisticaController;
use Src\Service\JurosService;

$app = AppFactory::create();

$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("API rodando com Slim!");
    return $response;
});


// Cria container para injeção de dependências
$container = new Container();

// Configura conexão PDO no container
$container->set(PDO::class, function() {
    return Database::getConnection();
});

// Configura DAOs no container, injetando PDO
$container->set(ProdutoDAO::class, function($c) {
    return new ProdutoDAO($c->get(PDO::class));
});
$container->set(CompraDAO::class, function($c) {
    return new CompraDAO($c->get(PDO::class));
});
$container->set(JurosDAO::class, function($c) {
    return new JurosDAO($c->get(PDO::class));
});
$container->set(EstatisticaDAO::class, function($c) {
    return new EstatisticaDAO($c->get(PDO::class));
});

// Configura Controllers no container, injetando seus DAOs
$container->set(JurosService::class, function() {
    return new JurosService(); // sem dependências
});
$container->set(ProdutoController::class, function($c) {
    return new ProdutoController($c->get(ProdutoDAO::class));
});
$container->set(CompraController::class, function($c) {
    return new CompraController($c->get(CompraDAO::class), $c->get(ProdutoDAO::class));
});
$container->set(JurosController::class, function($c) {
    return new JurosController(
        $c->get(JurosDAO::class),
        $c->get(JurosService::class)
    );
});
$container->set(EstatisticaController::class, function($c) {
    return new EstatisticaController($c->get(EstatisticaDAO::class));
});

// Define container para Slim usar
AppFactory::setContainer($container);

// Cria app Slim
$app = AppFactory::create();

// Ativa middleware para parsear JSON automaticamente
$app->addBodyParsingMiddleware();

// Registra rotas
(require __DIR__ . '/../src/routes/api.php')($app);

// Roda app
$app->run();