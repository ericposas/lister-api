<?php
// src/Controllers/BugController.php
namespace PHPapp\Controllers;

use PHPapp\Entity\Bug;
use PHPapp\AbstractResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BugController extends AbstractResource
{

  public function get(Request $req, Response $res, array $args)
  {
    $bugs = $this->getEntityManager()
    ->getRepository('PHPapp\Entity\Bug')
    ->findAll();

    $output = [];
    // each bug then grabs its own stored data on hydrate 
    foreach ($bugs as $bug) {
      $inner = [];
      $inner['Description'] = "{$bug->getDescription()} - {$bug->getCreated()}";
      $inner['Reported by'] = "{$bug->getReporter()->getName()}";
      $inner['Assigned to'] = "{$bug->getEngineer()->getName()}";
      foreach ($bug->getProducts() as $product) {
        $inner['Platform'] = "{$product->getName()}";
      }
      $output[] = $inner;
    }
    return $res->withJson([
      'messages' => $output,
      'count' => count($output)
    ]);
  }
  
  public function post(Request $req, Response $res, array $args)
  {
    return $res->withJson([
      'message' => 'a JSON response from the :post method',
      'params' => $args
    ], 201);
  }

}