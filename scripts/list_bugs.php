<?php
// list_bugs.php
require_once __DIR__."/../bootstrap.php";

use PHPapp\Entity\Bug;

// above dql seems unecessary bc we can get all via the following..
$bugs = $entityManager->getRepository(Bug::class)->findAll();

// each bug then grabs its own stored data on hydrate 
foreach ($bugs as $idx => $bug) {
  if ($idx === 0) {
    echo "Bugs marked as 'OPEN' \n\n";
  }
  if ($bug->getStatus() === 'OPEN') {
    $bool = (bool)($bugs[$idx] === $bug);
    if ($bool === TRUE) {
      echo "is \$bugs[idx] equal to \$bug? true \n\n";
      echo "bool still prints out as a {$bool} even though its cast using (bool) \n\n";
    } else {
      echo "bugs[idx] is not equal to bug \n\n";
    }
    echo "{$bugs[$idx]->getDescription()} - {$bug->getCreated()} \n";
    echo "      Reported by: {$bug->getReporter()->getName()} \n";
    echo "      Assigned to: {$bug->getEngineer()->getName()} \n";
    foreach ($bug->getProducts() as $product) {
      echo "      Platform: {$product->getName()} \n";
    }
    echo "\n";
  }
}
