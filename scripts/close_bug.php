<?php
// close_bug.php <bug-id>
require_once __DIR__.'/../bootstrap.php';

if (empty($argv[1])) {
  echo "You need to provide a bug-id to close out \n\n";
  exit(1);
}

$id = $argv[1];
// $bug = $entityManager->getRepository("Bug")->findOneBy(['id' => $id]);
$bug = $entityManager->find('Bug', (int)$id);

if ($bug->getStatus() === 'OPEN') {
  $bug->close();
  $entityManager->flush();
  echo "Successfully closed {$id} \n\n";
} else {
  echo "no bug found with that id \n\n";
}
