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
    
}