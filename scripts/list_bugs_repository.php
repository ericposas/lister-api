<?php
// scripts/list_bugs_repository.php
require_once __DIR__."/../bootstrap.php";

// Now using BugRepository extended class with custom DQL logic
$bugs = $entityManager->getRepository('Bug')->getRecentBugs();

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
