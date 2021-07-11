<?php
// dashboard.php <user-id>
require_once 'bootstrap.php';

if (empty($argv[1])) {
  echo "Please provide a user-id \n";
  exit(1);
}

$id = $argv[1];

// $dql = "SELECT b, e, r FROM Bug b JOIN b.engineer e JOIN b.reporter WHERE b.status = 'OPEN' AND (e.id = ?1 OR r.id = ?1) ORDER BY b.created DESC";
$bugs = $entityManager->getRepository('Bug')->findAll();
$openBugs = [];
foreach ($bugs as $bug) {
  if ($bug->getStatus() === 'OPEN' && ((int)$bug->getEngineer()->getId() === (int)$id || (int)$bug->getReporter()->getId() === (int)$id)) {
    $openBugs[] = $bug;
  }
}

$count = count($openBugs);
echo "You have created or assigned to {$count} open bugs: \n\n";
// echo count($openBugs);

foreach ($openBugs as $bug) {
  echo "{$bug->getId()} - {$bug->getDescription()} \n\n";
}

// try {
//   $myBugs = $entityManager->createQuery($dql)
//             ->setParameter(1, $id)
//             ->setMaxResults(15)
//             ->getResult();
//   echo "You have created or assigned to {count($myBugs)} open bugs: \n\n";
  
//   foreach ($myBugs as $bug) {
//     echo "{$bug->getId()} - {$bug->getDescription()} \n\n";
//   }
// } catch (Doctrine\ORM\Query\QueryException $qe) {
//   echo "Exception occurred - \n\n";
//   echo $qe;
// }
