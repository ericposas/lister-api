<?php

namespace PHPapp\Controllers;

use OpenApi;

/**
 * Description of DocumentationController
 *
 * @author webdev00
 */
class DocumentationController {
    
    protected $container = null;
    
    protected $twig = null;

    # need to pass the DI Container here in order to get our container dependencies for use in the class 
    public function __construct(\Psr\Container\ContainerInterface $container) {
        $this->container = $container;
        $this->twig = $container->get("twig");
    }

    public function jsonResponse($request, $response, $args) {
        $dir = \OpenApi\scan([ __DIR__ . "/../../src/Schemas", __DIR__ . "/../Helpers/Routes.php" ]);
        $response->getBody()->write(json_encode($dir));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function view($request, $response, $args)
    {
        $response->getBody()->write(
            $this->twig->render("profile.html.twig")
        );
        return $response;
    }
    
}