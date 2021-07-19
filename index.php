<?php

// defined absolute path here for use in other files
// define('CWD', getcwd());

require 'vendor/autoload.php';

// Slim framework 
use Slim\Factory\AppFactory;
use Slim\Http\Response as Response;
use PHPapp\Controllers\UserController;
use PHPapp\Controllers\ItemController;
use PHPapp\Controllers\ListsController;
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

$app->GET('/', function(Request $request, Response $response)
{
    return $response->write("<div>Nothin</div>");
});

///////////////////////////////////////////////////////
//
// USERS
//
///////////////////////////////////////////////////////

/**
 * GET getUsers
 * Summary: gets all Users
 * Notes: Gets all User models
 * Output-Formats: [application/json] 
 */
$app->GET('/users', UserController::class . ':index');

/**
 * POST createUser
 * Summary: create a new User
 * Notes: Creates a new User model
 * Output-Formats: [application/json]
 * @param name: string - REQUEST BODY 
 * @param contact: { email: string, phone: string } - REQUEST BODY 
 */
$app->POST('/users', UserController::class . ':create');

/**
 * PUT updateUserContactInfo
 * Summary: update a User's Contact card
 * Output-Formats: [application/json]
 * @param id: int- URL PARAM
 * @param contact: { email: string, phone: string } - REQUEST BODY 
 */
$app->PUT("/users/{id}/contact", UserController::class . ':updateUserContactInfo');

/**
 * POST createList
 * Summary: Add a new List object to a User
 * @param id: int User ID -- URL PARAM
 */
$app->POST("/users/{id}/lists", UserController::class . ':createList');

/**
 * GET getUser
 * Summary: get a single User by id
 * Notes: Find a user by passing in the user's id 
 * Output-Formats: [application/json]
 * @param int $id
 * @return JSON { message, user { name, contact info, lists } }
 */
$app->GET('/users/{id}', UserController::class . ':show');


///////////////////////////////////////////////////////
//
// LISTS
//
///////////////////////////////////////////////////////

/**
 * CREATE Item
 * Summary: creates a new Item and attaches it to a List
 * @param int $id URL PARAM -- id of the List object you'd
 * ..like to attach the new Item to
 */
$app->POST('/lists/{id}/item', ListsController::class . ':createItem');

///////////////////////////////////////////////////////
//
// ITEMS
//
///////////////////////////////////////////////////////

/**
 * GET Item
 * Summary: get a single User by id
 * Notes: Find a user by passing in the user's id 
 * Output-Formats: [application/json]
 * @param int $id
 * @return JSON { string "message", Item "item" }
 */
$app->GET('/items/{id}', ItemController::class . ':show');

/**
 * UPDATE
 * Summary: Update an existing list item
 * @param JSON { string "name", JSON blob "meta", string "icon" }
 */
$app->PUT('/items/{id}', ItemController::class . ':update');

$app->run();
