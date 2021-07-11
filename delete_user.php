<?php
// delete_user.php <name>
require_once 'bootstrap.php';

if (empty($argv[1])) {
  echo "Please provide a User name to delete \n";
  exit(1);
}

$name = $argv[1];

try {
  $user = $entityManager->getRepository('User')->findOneBy(['name' => $name]);
  if (isset($user)) {
    $entityManager->remove($user);
    $entityManager->flush();
    echo "Deleted User {$user->getName()} \n";
  } else {
    echo "Could not find the specified user. \n";
  }
} catch (Doctrine\ORM\Query\QueryException $qe) {
  echo $qe;
  exit(1);
}
