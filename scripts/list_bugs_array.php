<?php
// list_bugs_array.php
require_once __DIR__.'/../bootstrap.php';

$dql = "SELECT b, e, r, p FROM Bug b JOIN b.engineer e
JOIN b.reporter r JOIN b.products p ORDER BY b.created DESC";

try {
  $query = $entityManager->createQuery($dql);
  $bugs = $query->getArrayResult();
} catch (Doctrine\ORM\Query\QueryException $exception) {
  echo $exception;
  exit(1);
}

foreach ($bugs as $bug) {
  echo "{$bug['description']} - {$bug['created']} \n";
  echo "      Reported by: {$bug['reporter']['name']} \n";
  echo "      Assigned to: {$bug['engineer']['name']} \n";
  foreach ($bug['products'] as $product) {
    echo "      Platform: {$product['name']} \n";
  }
  echo "\n";
}
