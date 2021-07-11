<?php
// create_user.php <name>
require_once __DIR__."/../bootstrap.php";

if (empty($argv[1])) {
  echo "Please provide a name for new User \n";
  exit(1);
}

$newUsername = $argv[1];

$user = new User($newUsername); // included call to ->setName() in __construct()
// $user->setName($newUsername);

$entityManager->persist($user);
$entityManager->flush();

echo "Created User with ID {$user->getId()} \n";