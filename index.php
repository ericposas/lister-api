<?php

require 'vendor/autoload.php';

// Slim framework 
use Slim\Factory\AppFactory;
use Slim\Http\Response as Response;
use PHPapp\Controllers\UserController;
use PHPapp\Controllers\ItemController;
use PHPapp\Controllers\ListsController;
use PHPapp\Controllers\ContactController;
use Psr\Http\Message\ServerRequestInterface as Request;

// Instantiate App
$app = AppFactory::create();

// Add middleware
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

/////////////////////////////////////////////////////
//
//  USERS
//
/////////////////////////////////////////////////////

# Gets all Users 
$app->get("/users", UserController::class . ":index");

# Gets a single User by $id
$app->get("/users/{id}", UserController::class . ":show");

# Creates a new User
/**
 * @param requestBody $body { name, email?, phone? }
 */
$app->post("/users", UserController::class . ":create");

# Deletes a User
$app->delete("/users/{id}", UserController::class . ":delete");

# Creates a new List and attaches to a User at $id
$app->post("/users/{id}/list", ListsController::class . ":create");

# Adds or Updates User contact data
/**
 * @param requestBody $body { email?, phone? }
 */
$app->post("/users/{id}/contact", PHPapp\Controllers\ContactController::class . ":create");

/////////////////////////////////////////////////////
//
//  CONTACT
//
/////////////////////////////////////////////////////

$app->delete("/contacts/{id}", PHPapp\Controllers\ContactController::class . ":delete");


/////////////////////////////////////////////////////
//
//  LISTS
//
/////////////////////////////////////////////////////

$app->get("/lists", ListsController::class . ":index");

# Deletes a List of $id
$app->delete("/lists/{id}", ListsController::class . ":delete");

$app->post("/lists/{id}/item", ItemController::class . ":create");


$app->run();

?>
