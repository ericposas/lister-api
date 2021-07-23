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

# Get a single Users lists by User id
$app->get("/users/{id}/lists", UserController::class . ":showLists");

# Creates a new User
/**
 * @param requestBody $body { name, email?, phone? }
 */
$app->post("/users", UserController::class . ":create");

# Deletes a User
$app->delete("/users/{id}", UserController::class . ":delete");

/////////////////////////////////////////////////////
//
//  CONTACT
//
/////////////////////////////////////////////////////

# Adds or Updates User contact data
/**
 * @param requestBody $body { email?, phone? }
 */
$app->post("/users/{id}/contact", PHPapp\Controllers\ContactController::class . ":create");

# Delete a Contact by Contact id
$app->delete("/contacts/{id}", PHPapp\Controllers\ContactController::class . ":delete");

/////////////////////////////////////////////////////
//
//  LISTS
//
/////////////////////////////////////////////////////

# Get all Lists
$app->get("/lists", ListsController::class . ":index");

# Get and show a List by id
$app->get("/lists/{id}", ListsController::class . ":show");

# Creates a new List and attaches to a User at $id 
$app->post("/users/{id}/list", ListsController::class . ":create");

# Deletes a List of $id
$app->delete("/lists/{id}", ListsController::class . ":delete");


/////////////////////////////////////////////////////
//
//  ITEMS
//
/////////////////////////////////////////////////////

# Creates a new Item and assigns to designated List id
$app->post("/lists/{id}/item", ItemController::class . ":create");

# Updates and Item by id
$app->put("/items/{id}", ItemController::class . ":update");


$app->run();

