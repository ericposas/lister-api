<?php

require 'vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;
use PHPapp\Controllers\HomeController;
use PHPapp\Controllers\UserController;
use PHPapp\Controllers\ItemController;
use PHPapp\Controllers\ListsController;
use PHPapp\Controllers\LoginController;
use PHPapp\Controllers\TokenController;
use PHPapp\Controllers\LogoutController;
use PHPapp\Controllers\ContactController;
use PHPapp\Controllers\AuthCodeController;
use PHPapp\Controllers\ManageTokensController;
use PHPapp\Controllers\DocumentationController;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use PHPapp\Middleware\CountMiddleware;
use PHPapp\Middleware\VerifyJWTMiddleware;

use Auth0\SDK\Auth0;

use OpenApi\Annotations as OA;

use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Slim\Views\TwigMiddleware;

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

if ($_ENV["ENV"] === "local") {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.local")->load();
} else {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.stage")->load();
}

/////////////////////////////////////////////////////
//
//  SLIM
//
/////////////////////////////////////////////////////

// Create Container
$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

/////////////////////////////////////////////////////
//
//  TWIG TEMPLATES
//
/////////////////////////////////////////////////////

// ......

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

$app->get("/", HomeController::class);

/////////////////////////////////////////////////////
//
//  API TOKENS -- Only available to logged in API Users
//
/////////////////////////////////////////////////////

# Delete token
$app->get("/delete-token/{id}", TokenController::class . ":delete");

# Generate token
$app->get("/generate-new-token", TokenController::class . ":generate");

/////////////////////////////////////////////////////
//
//  API DOCS
//
/////////////////////////////////////////////////////

/**
 * @OA\Info(title="Lister API", version="0.1")
 */

 /**
 * @OA\Get(
 *      path="/api-docs",
 *      tags={"documentation"},
 *      summary="OpenAPI JSON File that describes the API",
 *      @OA\Response(
 *          response="200",
 *          description="OpenAPI Description File"
 *      ),
 * )
 */
$app->get("/api-docs", DocumentationController::class . ":jsonResponse");

/**
 * @OA\Get(
 *      path="/documentation",
 *      summary="Lister API documentation",
 *      @OA\Response(
 *          response="200",
 *          description="HTML Generated Swagger OpenAPI docs for Lister API"
 *      ),
 * )
 */ 
$app->get("/documentation", DocumentationController::class . ":view");

/////////////////////////////////////////////////////
//
//  USERS
//
/////////////////////////////////////////////////////

/**
 * @OA\Get(
 *      path="/users",
 *      summary="Endpoint for listing all created Users",
 *      @OA\Response(
 *          response="200",
 *          description="Gets all users"
 *      ),
 * )
 */ 
$app->get("/users", UserController::class . ":index")->add(VerifyJWTMiddleware::class);

/**
 * @OA\Get(
 *      path="/users/{id}",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required="true",
 *          description="User identifying integer",
 *          @OA\Schema(type="int")
 *      ),
 *      summary="Endpoint for retrieving a single User entity",
 *      @OA\Response(
 *          response="200",
 *          description="Gets a single User"
 *      ),
 * )
 */ 
$app->get("/users/{id}", UserController::class . ":show")->add(VerifyJWTMiddleware::class);

# Get a single Users lists by User id
$app->get("/users/{id}/lists", UserController::class . ":showLists")->add(VerifyJWTMiddleware::class);

# Creates a new User
/**
 * @param requestBody $body { name, email?, phone? }
 */
$app->post("/users", UserController::class . ":create")->add(VerifyJWTMiddleware::class);

# Deletes a User
$app->delete("/users/{id}", UserController::class . ":delete")->add(VerifyJWTMiddleware::class);


/////////////////////////////////////////////////////
//
//  CONTACT
//
/////////////////////////////////////////////////////

# Adds or Updates User contact data
/**
 * @param requestBody $body { email?, phone? }
 */
$app->post("/users/{id}/contact", ContactController::class . ":create")->add(VerifyJWTMiddleware::class);

# Delete a Contact by Contact id
$app->delete("/contacts/{id}", ContactController::class . ":delete")->add(VerifyJWTMiddleware::class);

# Update a Contact card by Contact id
//$app->put("/contacts/{id}", ContactController::class . ":update");


/////////////////////////////////////////////////////
//
//  LISTS
//
/////////////////////////////////////////////////////

# Get all Lists
$app->get("/lists", ListsController::class . ":index")->add(VerifyJWTMiddleware::class);

# Get and show a List by id
$app->get("/lists/{id}", ListsController::class . ":show")->add(VerifyJWTMiddleware::class);

# Creates a new List and attaches to a User at $id 
$app->post("/users/{id}/list", ListsController::class . ":create")->add(VerifyJWTMiddleware::class);

# Update a List by List id
//$app->put("/lists/{id}", ListsController::class . ":update");

# Deletes a List of $id
$app->delete("/lists/{id}", ListsController::class . ":delete")->add(VerifyJWTMiddleware::class);


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
$app->post("/lists/{id}/item", ItemController::class . ":create")->add(VerifyJWTMiddleware::class);

# Updates and Item by id
$app->put("/items/{id}", ItemController::class . ":update")->add(VerifyJWTMiddleware::class);

# Delete an Item by Item id
$app->delete("/items/{id}", ItemController::class . ":delete")->add(VerifyJWTMiddleware::class);


# Run Slim Framework
$app->run();

