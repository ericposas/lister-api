<?php
// list_bugs.php
require_once '../bootstrap.php';

// $dql = "SELECT b, e, r FROM Bug b JOIN b.engineer e JOIN b.reporter r ORDER BY b.created DESC";

// $query = $entityManager->createQuery($dql);
// $query->setMaxResults(30);
// $bugs = $query->getResult();

// above dql seems unecessary bc we can get all via the following..
$bugs = $entityManager->getRepository('Bug')->findAll();

// each bug then grabs its own stored data on hydrate 
foreach ($bugs as $bug) {
  // $formatted_date = $bug->getCreated()->format('d.m.Y');
  echo "{$bug->getDescription()} - {$bug->getCreated()} \n";
  echo "      Reported by: {$bug->getReporter()->getName()} \n";
  echo "      Assigned to: {$bug->getEngineer()->getName()} \n";
  foreach ($bug->getProducts() as $product) {
    echo "      Platform: {$product->getName()} \n";
  }
  echo "\n";
}
