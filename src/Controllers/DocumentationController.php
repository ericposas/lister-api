<?php

namespace PHPapp\Controllers;

use OpenApi;

/**
 * Description of DocumentationController
 *
 * @author webdev00
 */
class DocumentationController {
    
    
    public function jsonResponse($request, $response, $args) {
        $swagger = \OpenApi\scan(__DIR__."/../../index.php");
        $response->getBody()->write(json_encode($swagger));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function view($request, $response, $args)
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/../../templates");
        $twig = new \Twig\Environment($loader, [
            __DIR__ . "/var/cache"
        ]);
        $response->getBody()->write(
            $twig->render("profile.html.twig")  //, [ "boop" => $args["boop"], "test" => "this is a test" ])
        );
        return $response;
    }
    
}