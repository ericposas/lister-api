<?php

require 'vendor/autoload.php';

# load base .env
\Dotenv\Dotenv::createImmutable(__DIR__)->load();
# load conditional .envs based on above ENV variable
if ($_ENV["ENV"] === "local") {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.local")->load();
} else {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.stage")->load();
}

/////////////////////////////////////////////////////
//
//  SLIM
//
/////////////////////////////////////////////////////

$app = \PHPapp\Helpers\AppHelper::createAppInstance();

# Run Slim Framework
$app->run();
