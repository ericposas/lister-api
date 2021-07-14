<?php

// defined absolute path here for use in other files
define('CWD', getcwd());

require 'vendor/autoload.php';

// Slim framework 
use Slim\Factory\AppFactory;
// use PHPapp\Controllers\TestController;
use PHPapp\Controllers\BugController;
use PHPapp\Controllers\UserController;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Instantiate App
$app = AppFactory::create();

// Add middleware
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

/**
 * Grocery List Estimator API
 * @version 1.0.1
 */

/**
 * POST createUser
 * Summary: createUser
 * Notes: Creates a new User model
 * Output-Formats: [application/json]
 * @param name: string - REQUEST BODY 
 * @param contact: { email: string, phone: string } - REQUEST BODY 
 */
$app->POST('/users', UserController::class . ':post');

/**
 * GET getUser
 * Summary: getUser
 * Notes: Find a user by passing in the user&#39;s id 
 * Output-Formats: [application/json]
 * @param id: int
 */
$app->GET('/users/{id}', UserController::class . ':get');

// Testing some Models 
$app->get('/bugs', BugController::class . ':get');
$app->post('/bugs/{reporter_id}/{engineer_id}/{product_ids}', BugController::class . ':post');


$app->run();

?>
