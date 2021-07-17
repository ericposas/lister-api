<?php

// defined absolute path here for use in other files
// define('CWD', getcwd());

require 'vendor/autoload.php';

// Slim framework 
use Slim\Factory\AppFactory;
use Slim\Http\Response as Response;
use PHPapp\Controllers\UserController;
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
 * Summary: create a new User
 * Notes: Creates a new User model
 * Output-Formats: [application/json]
 * @param name: string - REQUEST BODY 
 * @param contact: { email: string, phone: string } - REQUEST BODY 
 */
$app->POST('/users', UserController::class . ':createUser');

/**
 * GET getUsers
 * Summary: gets all Users
 * Notes: Gets all User models
 * Output-Formats: [application/json] 
 */
$app->GET('/users', UserController::class . ':getUsers');

/**
 * GET getUser
 * Summary: get a single User by id
 * Notes: Find a user by passing in the user's id 
 * Output-Formats: [application/json]
 * @param id: int
 */
$app->GET('/users/{id}', UserController::class . ':getSingleUser');

// Testing some Models 
// $app->get('/bugs', BugController::class . ':get');
// $app->post('/bugs/{reporter_id}/{engineer_id}/{product_ids}', BugController::class . ':post');

$app->run();

?>
