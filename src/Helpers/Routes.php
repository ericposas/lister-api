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
             *      @OA\Response(
             *          response="400",
             *          description="No Users added yet",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="We couldn't find any Users"
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
             *      @OA\Response(
             *          response="400",
             *          description="Could not get the User",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="Could not get the User"
             *              )
             *          )
             *      ),
             *      @OA\Response(
             *          response="404",
             *          description="If no $id is supplied",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="No User found with an id of {$id}"
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->get("/users/{id}", UserController::class . ":show");
            
            # get user by passing in User name in req. body 
            #$group->get("/users", UserController::class . ":getUserByName");
            
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
             *      @OA\Response(
             *          response="404",
             *          description="If the User does not exist",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  description="User does not exist"
             *              )
             *          )
             *      ),
             *      @OA\Response(
             *          response="400",
             *          description="If the User has not created any Lists",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  description="User {$user->getName()} has no lists"
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
             *          description="Message for successful creation of User",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="New user {$user->getName()} created."
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
             *                  example="{$user->getName()} User removed"
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->delete("/users/{id}", UserController::class . ":delete");
            
            # pass in email identifier via req. body to delete User by unique email
            #$group->delete("/users", UserController::class . ":deleteUserByEmail");

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
             *                  property="message",
             *                  example="Created new Contact info and attached to {$user->getName()}"
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
             *                  property="message",
             *                  example="Contact with id of {$contact->getId()} was deleted"
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
             *                  property="message",
             *                  example="Changed Contact info and attached to {$user->getName()}"
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
            
            /**
             * @OA\Get(
             *      path="/lists",
             *      tags={"Lists"},
             *      summary="Endpoint listing all Lists",
             *      @OA\Response(
             *          response="200",
             *          description="Returns a list of all defined Lists",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="lists",
             *                  type="array",
             *                  @OA\Items(
             *                      ref="#/components/schemas/GenericList"
             *                  )
             *              )
             *          )
             *      ),
             * )
             */ 
            $group->get("/lists", ListsController::class . ":index");

            /**
             * @OA\Get(
             *      path="/lists/{id}",
             *      tags={"Lists"},
             *      @OA\Parameter(
             *          name="id",
             *          in="path",
             *          required=true,
             *          description="id to query for GenericList entity",
             *          @OA\Schema(type="int")
             *      ),
             *      summary="Endpoint for retrieving a single GenericList entity",
             *      @OA\Response(
             *          response="200",
             *          description="Return a single List object with associated Items",
             *          @OA\JsonContent(ref="#/components/schemas/GenericList")
             *      ),
             * )
             */ 
            $group->get("/lists/{id}", ListsController::class . ":show");

            /**
             * @OA\Post(
             *      path="/users/{id}/list",
             *      tags={"Lists"},
             *      summary="Endpoint for creating a new List and attaching it to a User",
             *      @OA\Response(
             *          response="201",
             *          description="Message for successful creation of new List",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="Added a new list for User {$user->getName()}"
             *              )
             *          )
             *      ),
             * )
             */
            $group->post("/users/{id}/list", ListsController::class . ":create");

            /**
             * @OA\Put(
             *      path="/lists/{id}",
             *      tags={"Lists"},
             *      summary="Endpoint for updating an existing List",
             *      @OA\Response(
             *          response="201",
             *          description="Message for successful update of a List",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="Updated a List for User {$user->getName()}"
             *              )
             *          )
             *      ),
             * )
             */
            $group->put("/lists/{id}", ListsController::class . ":update");
            
            # pass list name as request body data
            #$group->put("/lists", ListsController::class . ":updateByListName");

            /**
             * @OA\Delete(
             *      path="/lists/{id}",
             *      tags={"Lists"},
             *      summary="Endpoint for deleting a single List entity",
             *      @OA\Response(
             *          response="200",
             *          description="Response for successful deletion of a List",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="Successfully deleted User {$list->getOwner()->getName()}'s List {$list->getName()}"
             *              )
             *          )
             *      ),
             * )
             */
            $group->delete("/lists/{id}", ListsController::class . ":delete");
            
            # pass list name as request body data..
            #$group->delete("/lists", ListsController::class . ":deleteByListName");

            /////////////////////////////////////////////////////
            //
            //  SHARES
            //
            /////////////////////////////////////////////////////
            
            #$group->get("/shares", SharesController::class . ":index");
            
            #$group->get("/shares/{id}", SharesController::class . ":show");
            
            # get shares by user id
            #$group->get("/users/{id}/shares", SharesController::class . ":getSharesByUserId");
            
            # create -- post some body data 
            #$group->post("/shares", SharesController::class . ":create");
            
            #$group->put("/shares/{id}", SharesController::class . ":update");
            
            #$group->delete("/shares/{id}", SharesController::class . ":delete");

            /////////////////////////////////////////////////////
            //
            //  ITEMS
            //
            /////////////////////////////////////////////////////

            # List all Items
            //$app->get("/items", ItemController::class . ":index");

            # Get a single Item by id
            //$app->get("/items/{id}", ItemController::class . ":show");

            /**
             * @OA\Post(
             *      path="/lists/{id}/item",
             *      tags={"Items"},
             *      summary="Endpoint for creating a new Item and adding it to a List",
             *      @OA\Response(
             *          response="201",
             *          description="Message for successful creation of new list Item",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="Added a new Item to List {$list->getName()}"
             *              )
             *          )
             *      ),
             * )
             */
            $group->post("/lists/{id}/item", ItemController::class . ":create")->add(VerifyJWTMiddleware::class);

            /**
             * @OA\Put(
             *      path="/items/{id}",
             *      tags={"Items"},
             *      summary="Endpoint for updating an existing Item",
             *      @OA\Response(
             *          response="201",
             *          description="Message for successful update of an Item",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="Updated an Item for List {$list->getName()}"
             *              )
             *          )
             *      ),
             * )
             */
            $group->put("/items/{id}", ItemController::class . ":update")->add(VerifyJWTMiddleware::class);

            /**
             * @OA\Delete(
             *      path="/items/{id}",
             *      tags={"Items"},
             *      summary="Endpoint for deleting a single Item entity",
             *      @OA\Response(
             *          response="200",
             *          description="Response for successful deletion of an Item",
             *          @OA\JsonContent(
             *              @OA\Property(
             *                  property="message",
             *                  example="Successfully deleted Item from List {$item->getParentlist()->getName()}"
             *              )
             *          )
             *      ),
             * )
             */
            $group->delete("/items/{id}", ItemController::class . ":delete")->add(VerifyJWTMiddleware::class);

        })->add(VerifyJWTMiddleware::class);
    }

}
