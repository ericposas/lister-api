<?php
// src/Controllers/UserController.php

namespace PHPapp\Controllers;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{

  public function get(Request $req, Response $res, array $args)
  {
    return $res->withJson([
      'message' => 'a JSON response from the :get method',
      'params' => $args
    ], 201);
  }

  public function post(Request $req, Response $res, array $args)
  {
    $body = json_decode($req->getBody());
    $name = $body->name;
    $contact = $body->contact;

    return $res->withJson([
      'message' => 'a JSON response from the :post method',
      'name' => $name,
      'email' => $contact->email,
      'phone' => $contact->phone,
    ], 201);
  }

}