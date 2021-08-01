<?php

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use PHPapp\Controllers\UserController;
use PHPapp\Controllers\ItemController;
use PHPapp\Controllers\ListsController;
use PHPapp\Controllers\ContactController;
use PHPapp\Controllers\LoginController;
use PHPapp\Controllers\LogoutController;
use PHPapp\Controllers\AuthCodeController;

use Auth0\SDK\Auth0;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use PHPapp\Middleware\CountMiddleware;

# jwt token parsing -- move to middleware __invoke-able class
use Auth0\SDK\Helpers\JWKFetcher;
use Auth0\SDK\Helpers\Tokens\AsymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\SymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\IdTokenVerifier;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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

$uselessInjectHelloMw = function (Request $request,  RequestHandler $handler) {
    $existing = $handler->handle($request)->getBody();
    $newResp = new Slim\Psr7\Response(); # <-- we have to use this other type of Response object in the middleware
    $newResp->getBody()->write(<<<EOL
        <div>
            {$existing}
            <h4>Helloooo</h4>
        </div>
    EOL);
    return $newResp;
};

$verifyJWT = function (Request $request, RequestHandler $handler) {
    
    $response = new Slim\Psr7\Response();
    
    $api_token = \PHPapp\Helpers\GetAuthorizationTokenFromHeader::getToken($request);
    
    if (empty($api_token)) {
        $response->getBody()->write((string) json_encode([
            "message" => "you need to log in to get a jwt, then you can make API calls"
        ]));
        $response = $response->withHeader("content-type", "application/json");
        return $response;
    }
    
    $token_issuer = "https://{$_ENV["AUTH0_DOMAIN"]}/";
    $jwks_fetcher = new JWKFetcher();
    $jwks = $jwks_fetcher->getKeys("{$token_issuer}.well-known/jwks.json");
    $signature_verifier = new AsymmetricVerifier($jwks);
    
    $token_verifier = new IdTokenVerifier(
            $token_issuer,
            $_ENV["AUTH0_CLIENT_ID"],
            $signature_verifier
    );
    
    try {
        $decoded_token = $token_verifier->verify($api_token);
        
        // check jwt, if good, then go ahead and allow api call to go through
        if (isset($decoded_token)) {
            $handled = $handler->handle($request)->getBody();
            $response = new Slim\Psr7\Response();
            $response->getBody()->write((string) $handled);
            return $response->withAddedHeader("content-type", "application/json");
        } else {
            $response->getBody()->write((string) json_encode([
                "user" => "user has no token in session, is not logged in"
            ]));
            return $response->withAddedHeader("content-type", "application/json");
        }
        
    } catch (Exception $ex) {
        $response->getBody()->write("Caught Exception - {$ex->getMessage()}");
        return $response;
    }
    
};

/////////////////////////////////////////////////////
//
//  LOGIN
//
/////////////////////////////////////////////////////

$app->get("/login", LoginController::class);

$app->get("/logout", LogoutController::class);

///////////////////////////////////////////////////////
//
//  PUBLIC STATIC
//
/////////////////////////////////////////////////////

$app->get("/", function (Request $request, Response $response) {
    
    $api_token = \PHPapp\Helpers\GetAuthorizationTokenFromHeader::getToken($request);
    
    if (isset($api_token)) {
        return $response->withJson([
            "status" => "you are authorized to make API calls with the provided token",
            "authorized" => true
        ]);
    }
    
    return $response->withJson([
        "status" => "not authorized to access API",
        "authorized" => false
    ]);
    
})->add($verifyJWT);

//$app->get("/", function (Request $request, Response $response) {
//    return $response->write("<h2>Home Updated.</h2>");
//})->add(CountMiddleware::class . ":appendUserCount");

/////////////////////////////////////////////////////
//
//  USERS
//
/////////////////////////////////////////////////////

# Gets all Users 
$app->get("/users", UserController::class . ":index")->add($verifyJWT);

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
$app->delete("/items/{id}", ItemController::class . ":delete");


# Run Slim Framework
$app->run();

