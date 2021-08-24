<?php

namespace PHPapp\Helpers;

use PHPapp\Controllers\HomeController;
use PHPapp\Controllers\UserController;
use PHPapp\Controllers\ItemController;
use PHPapp\Controllers\ListsController;
use PHPapp\Controllers\LoginController;
use PHPapp\Controllers\TokenController;
use PHPapp\Controllers\LogoutController;
use PHPapp\Controllers\ContactController;
use PHPapp\Controllers\ManageTokensController;
use PHPapp\Controllers\DocumentationController;
use PHPapp\Middleware\VerifyJWTMiddleware;

/**
 * Description of MakeRoutes
 *
 * @author webdev00
 */
class Routes
{
    public static function createRoutes(\Slim\App $app) {
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

        $app->get("/api-docs", DocumentationController::class . ":jsonResponse");

        $app->get("/documentation", DocumentationController::class . ":view");

        /////////////////////////////////////////////////////
        //
        //  API GROUP
        //
        /////////////////////////////////////////////////////

        $app->group("", function (\Slim\Routing\RouteCollectorProxy $group) {

            /////////////////////////////////////////////////////
            //
            //  USERS
            //
            /////////////////////////////////////////////////////

            /**
             * @OA\Get(
             *      path="/users",
             *      tags={"Users"},
             *      summary="Endpoint for listing all created Users",
             *      @OA\Response(
             *          response="200",
             *          description="Response lists all Users with associated Lists, Contact info, etc.",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="users",
             *                  type="array",
             *                  @OA\Items(
             *                      ref="#/components/schemas/User"
             *                  )
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->get("/users", UserController::class . ":index");

            /**
             * @OA\Get(
             *      path="/users/{id}",
             *      tags={"Users"},
             *      @OA\Parameter(
             *          name="id",
             *          in="path",
             *          required=true,
             *          description="id to query for User",
             *          @OA\Schema(type="int")
             *      ),
             *      summary="Endpoint for retrieving a single User entity",
             *      @OA\Response(
             *          response="200",
             *          description="Return a single User object with associated Lists, Contact info, etc.",
             *          @OA\JsonContent(ref="#/components/schemas/User")
             *      ),
             * )
             */ 
            $group->get("/users/{id}", UserController::class . ":show");

            /**
             * @OA\Get(
             *      path="/users/{id}/lists",
             *      tags={"Lists"},
             *      @OA\Parameter(
             *          name="id",
             *          in="path",
             *          required=true,
             *          description="id of User whose Lists to retrieve",
             *          @OA\Schema(type="int")
             *      ),
             *      summary="Endpoint for retrieving a single User's List entities",
             *      @OA\Response(
             *          response="200",
             *          description="Gets a single User's List objects",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="lists",
             *                  type="array",
             *                  @OA\Items(
             *                      ref="#/components/schemas/GenericList")
             *                  )
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->get("/users/{id}/lists", UserController::class . ":showLists");

            /**
             * @OA\Post(
             *      path="/users",
             *      tags={"Users"},
             *      summary="Endpoint for creating a new User",
             *      @OA\Response(
             *          response="201",
             *          description="Message for successful cretion of User",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="New user USER->NAME created."
             *              )
             *          )
             *      ),
             * )
             */
            $group->post("/users", UserController::class . ":create");

            /**
             * @OA\Delete(
             *      path="/users/{id}",
             *      tags={"Users"},
             *      summary="Endpoint for deleting a User by id",
             *      @OA\Response(
             *          response="200",
             *          description="Message for successful deletion of a specified User",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="USER->NAME User removed"
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->delete("/users/{id}", UserController::class . ":delete");

            /////////////////////////////////////////////////////
            //
            //  CONTACT
            //
            /////////////////////////////////////////////////////

            /**
             * @OA\Post(
             *      path="/users/{id}/contact",
             *      tags={"Contact"},
             *      summary="Endpoint for creating a new Contact info and attaching to a specified User",
             *      @OA\Response(
             *          response="200",
             *          description="Creates Contact info for a specified User",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="Contact",
             *                  example="Created new Contact info and attached to USER->NAME"
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->post("/users/{id}/contact", ContactController::class . ":create");

            /**
             * @OA\Delete(
             *      path="/contacts/{id}",
             *      tags={"Contact"},
             *      summary="Endpoint for deleting a single Contact entity",
             *      @OA\Response(
             *          response="200",
             *          description="Response for successful deletion of Contact info",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="Contact",
             *                  example="Contact with id of INTEGER was deleted"
             *              )
             *          )
             *      ),
             * )
             */
            $group->delete("/contacts/{id}", ContactController::class . ":delete");

            /**
             * @OA\Put(
             *      path="/contacts/{id}",
             *      tags={"Contact"},
             *      summary="Endpoint for updating an existing Contact info",
             *      @OA\Response(
             *          response="200",
             *          description="Returns a success message upon updating a Contact info entity",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="Contact",
             *                  example="Changed Contact info and attached to USER->NAME"
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->put("/users/{id}/contact", ContactController::class . ":update");

            /////////////////////////////////////////////////////
            //
            //  LISTS
            //
            /////////////////////////////////////////////////////

            # Get all Lists    
            $group->get("/lists", ListsController::class . ":index");

            # Get and show a List by id        
            $group->get("/lists/{id}", ListsController::class . ":show");

            # Creates a new List and attaches to a User at $id 
            $group->post("/users/{id}/list", ListsController::class . ":create");

            # Update a List by List id
            //$app->put("/lists/{id}", ListsController::class . ":update");

            # Deletes a List of $id
            $group->delete("/lists/{id}", ListsController::class . ":delete");

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
            $group->post("/lists/{id}/item", ItemController::class . ":create")->add(VerifyJWTMiddleware::class);

            # Updates and Item by id
            $group->put("/items/{id}", ItemController::class . ":update")->add(VerifyJWTMiddleware::class);

            # Delete an Item by Item id
            $group->delete("/items/{id}", ItemController::class . ":delete")->add(VerifyJWTMiddleware::class);

        })->add(VerifyJWTMiddleware::class);
    }

}
