<?php
// create_product.php <name>
require_once __DIR__."/../bootstrap.php";

$newProductName = $argv[1]; // argument from command line

$product = new Product();
$product->setName($newProductName);

$entityManager->persist($product);
$entityManager->flush();

echo "Created Product with ID {$product->getId()} \n";