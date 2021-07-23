<?php

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use PHPapp\Controllers\UserController;
use PHPapp\Controllers\ItemController;
use PHPapp\Controllers\ListsController;
use PHPapp\Controllers\ContactController;

/////////////////////////////////////////////////////
//
//  SLIM
//
/////////////////////////////////////////////////////

$app = AppFactory::create();
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
$app->post("/users/{id}/contact", ContactController::class . ":create");

# Delete a Contact by Contact id
$app->delete("/contacts/{id}", ContactController::class . ":delete");

# Update a Contact card by Contact id
//$app->put("/contacts/{id}", ContactController::class . ":update");


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

# Update a List by List id
//$app->put("/lists/{id}", ListsController::class . ":update");

# Deletes a List of $id
$app->delete("/lists/{id}", ListsController::class . ":delete");


/////////////////////////////////////////////////////
//
//  SHARES
//
/////////////////////////////////////////////////////


/////////////////////////////////////////////////////
//
//  ITEMS
//
/////////////////////////////////////////////////////

# List all Items
//$app->get("/items", ItemController::class . ":index");

# Get a single Item by id
//$app->get("/items/{id}", ItemController::class . ":show");

# Creates a new Item and assigns to designated List id
$app->post("/lists/{id}/item", ItemController::class . ":create");

# Updates and Item by id
$app->put("/items/{id}", ItemController::class . ":update");

# Delete an Item by Item id
//$app->delete("/items/{id}", ItemController::class . ":delete");


# Run Slim Framework
$app->run();

