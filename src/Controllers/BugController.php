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
  
  public function post(Request $req, Response $res, array $params)
  {

    if (
      isset($params) && $params['reporter_id'] &&
      $params['engineer_id'] && $params['product_ids']) {
      
      $em = $this->getEntityManager();

      $productIds = explode(',', $params['product_ids']);
      $reporter = $em->find('PHPapp\Entity\User', $params['reporter_id']);
      $engineer = $em->find('PHPapp\Entity\User', $params['engineer_id']);
      
      $bug = new Bug();
      $bug->setReporter($reporter);
      $bug->setEngineer($engineer);
      $bug->setDescription("Something does not work!");
      $bug->setCreated(new \DateTime("now"));
      $bug->setStatus("OPEN");
  
      foreach ($productIds as $productId) {
        $product = $this->getEntityManager()->find('PHPapp\Entity\Product', $productId);
        $bug->assignToProduct($product);
      }

      $em = $this->getEntityManager();
      $em->persist($bug);
      $em->flush();
  
      return $res->withJson([
        'success' => true,
        'message' => "Bug {$bug->getId()} was created by {$bug->getReporter()->getName()}."
      ], 201);

    } else {
      return $res->withJson([
        'success' => false,
        'message' => 'please provide correct parameters'
      ], 400);
    }

  }

}