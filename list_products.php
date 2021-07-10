<?php
// list_products.php
require_once 'bootstrap.php';
require 'src/Product.php';

$productRepository = $entityManager->getRepository('Product');
$products = $productRepository->findAll();

foreach ($products as $product) {
  echo sprintf("-%s\n", $product->getName());
}