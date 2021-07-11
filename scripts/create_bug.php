<?php
// create_bug.php <reporter-id> <engineer-id> <product-id>
require_once __DIR__."/../bootstrap.php";

if (empty($argv[1]) || empty($argv[2]) || empty($argv[3])) {
  echo "Please provide reported-id, engineer-id, and product-ids (comma-delimited list)";
  exit(1);
}

$reporterId = $argv[1];
$engineerId = $argv[2];
$productIds = explode(",", $argv[3]);

$reporter = $entityManager->find("User", $reporterId);
$engineer = $entityManager->find("User", $engineerId);
if (!$reporter || !$engineer) {
  echo "No reporter and/or engineer found for the given id(s). \n";
  exit(1);
}

$bug = new Bug();
$bug->setDescription("Something does not work!");
$bug->setCreated(new DateTime("now"));
$bug->setStatus("OPEN");

foreach ($productIds as $productId) {
  $product = $entityManager->find("Product", $productId);
  $bug->assignToProduct($product);
}

$bug->setReporter($reporter);
$bug->setEngineer($engineer);

$entityManager->persist($bug);
$entityManager->flush();

echo "Your new Bug ID {$bug->getId()} \n";