<?php

namespace PHPapp\Helpers;

class EnvHelper
{
    
    public static function getEnv($pathAppend)
    {
        $env = \Dotenv\Dotenv::createImmutable(__DIR__ . $pathAppend ? $pathAppend : "");
        $env->load();

        $whichEnv = $_ENV["ENV"] == "local" ? "local.env" : "stage.env";
        $mainenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "{$pathAppend}/{$whichEnv}");
        $mainenv->load();
    }
    
}