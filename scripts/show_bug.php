<?php
// show_bug.php <id>
require_once '../bootstrap.php';

if (empty($argv[1])) {
  echo "You must supply an id to lookup \n";
  exit(1);
}

$id = $argv[1];
$bug = $entityManager->find("Bug", (int)$id);

$result;
try {
  // get all bugs..
  $dql = "SELECT b, e FROM Bug b JOIN b.engineer e";
  $result = $entityManager->createQuery($dql)->getArrayResult();
} catch (Doctrine\ORM\Query\QueryException $qe) {
  echo $qe;
  exit(1);
}

echo "Bug: {$bug->getDescription()} \n";
echo "Engineer: {$bug->getEngineer()->getName()} \n";
echo "Reporter: {$bug->getReporter()->getName()} \n\n";

// echo json_encode($result);

// so have to iterate over 
foreach ($result as $bug) {
  echo "grabbing engineer: { name } value from direct dql statement \n";
  echo "{$bug['engineer']['name']} \n";
}
