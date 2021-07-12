<?php

// defined absolute path here for use in other files
define('CWD', getcwd());

require 'vendor/autoload.php';

// Slim framework 
use Slim\Factory\AppFactory;
use PHPapp\Controllers\TestController;
use PHPapp\Controllers\BugController;
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
$app->get('/test/{arg1}/{arg2}', TestController::class . ':get');
$app->post('/test/[{arg1}]', TestController::class . ':post');

// Testing some Models 
$app->get('/bugs', BugController::class . ':get');
$app->post('/bugs/{reporter_id}/{engineer_id}/{product_ids}', BugController::class . ':post');

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run();

?>
