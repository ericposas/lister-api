<?php

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use PHPapp\Controllers\UserController;
use PHPapp\Controllers\ItemController;
use PHPapp\Controllers\ListsController;
use PHPapp\Controllers\LoginController;
use PHPapp\Controllers\TokenController;
use PHPapp\Controllers\LogoutController;
use PHPapp\Controllers\ContactController;
use PHPapp\Controllers\AuthCodeController;
use PHPapp\Controllers\ManageTokensController;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use PHPapp\Middleware\CountMiddleware;
use PHPapp\Middleware\VerifyJWTMiddleware;

use Auth0\SDK\Auth0;

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

/////////////////////////////////////////////////////
//
//  SLIM
//
/////////////////////////////////////////////////////

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

/////////////////////////////////////////////////////
//
//  MIDDLEWARE
//
/////////////////////////////////////////////////////

// ......

/////////////////////////////////////////////////////
//
//  GET API TOKEN
//
/////////////////////////////////////////////////////

$app->get("/my-tokens", ManageTokensController::class); # also handles the generation of new tokens by redirecting back to this route

$app->get("/login", LoginController::class);

$app->get("/logout", LogoutController::class);

///////////////////////////////////////////////////////
//
//  PUBLIC STATIC
//
/////////////////////////////////////////////////////

$app->get("/", function (Request $request, Response $response) {
    $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
    $auth0 = new Auth0($auth0Config);

    $userInfo = $auth0->getUser();

    echo "<h1>Lister API</h1>";

    if (!$userInfo) {
        $html = "<div>"
                . "<div>Log in to manage your API tokens</div>"
                . "<div>"
                . "<a href=\"/login\"><button style=\"font-size: 1rem; margin: .75rem 0 0 0;\">Dashboard</button></a>"
                . "</div>"
              . "</div>";
    } else {
        $html = "<div>"
                . "<div>You are now logged in as {$auth0->getUser()["name"]}<div>"
                . "<div>"
                        . "<a href=\"/my-tokens\"><button style=\"font-size: 1rem; margin: .75rem 0 0 0\">Go to my tokens</button></a>"
                . "</div>"
              . "</div>";
    }
    $response->getBody()->write($html);
    return $response;
});

//$app->get("/", function (Request $request, Response $response) {
//    return $response->write("<h2>Home Updated.</h2>");
//})->add(CountMiddleware::class . ":appendUserCount");

/////////////////////////////////////////////////////
//
//  API TOKENS
//
/////////////////////////////////////////////////////

# Delete token
$app->get("/delete-token/{id}", TokenController::class . ":delete");

# Generate token
$app->get("/generate-new-token", TokenController::class . ":generate");

/////////////////////////////////////////////////////
//
//  USERS
//
/////////////////////////////////////////////////////

# Gets all Users 
$app->get("/users", UserController::class . ":index")->add(VerifyJWTMiddleware::class);

# Gets a single User by $id
$app->get("/users/{id}", UserController::class . ":show");

# Get a single Users lists by User id
$app->get("/users/{id}/lists", UserController::class . ":showLists");

# Creates a new User
/**
 * @param requestBody $body { name, email?, phone? }
 */
$app->post("/users", UserController::class . ":create")->add(VerifyJWTMiddleware::class);

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
$app->delete("/items/{id}", ItemController::class . ":delete");


# Run Slim Framework
$app->run();

