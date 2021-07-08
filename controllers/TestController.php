<?php

namespace Controllers;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TestController
{
  
  // if we have no other CRUD operations, we can just use the
  // __invoke magic method 

  // public function __invoke(Request $request, Response $response, $args = [])
  // {
  //   return $response->withJson([
  //       'message' => 'a JSON response for you good sir',
  //       'params' => $args
  //   ], 201);
  // }

  public function get(Request $req, Response $res, array $args)
  {
    return $res->withJson([
      'message' => 'a JSON response from the :get method',
      'params' => $args
    ], 201);
  }
  
  public function post(Request $req, Response $res, array $args)
  {
    return $res->withJson([
      'message' => 'a JSON response from the :post method',
      'params' => $args
    ], 201);
  }

}