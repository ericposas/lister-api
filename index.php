<?php

require 'vendor/autoload.php';
require_once 'controllers/TestController.php';

// Slim framework 
use Slim\Factory\AppFactory;
use Controllers\TestController;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<div>Home</div>');
    return $response;
});

$app->get('/test', TestController::class . ':get');
$app->get('/test/[{arg1}]', TestController::class . ':get');
$app->post('/test/[{arg1}]', TestController::class . ':post');

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run();

?>
