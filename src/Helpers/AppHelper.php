<?php

namespace PHPapp\Helpers;

/**
 * Description of AppHelper
 *
 * @author webdev00
 */
class AppHelper {
    
    /**
     * @return \Slim\App returns a DI configured instance of the Slim App class
     */
    public static function createAppInstance() {
        
        $containerBuilder = new \DI\ContainerBuilder();
        $containerBuilder->addDefinitions([
            "settings" => function() {
                $settings = [];
                // Path settings
                $settings['root'] = dirname(__DIR__);
                // Error Handling Middleware settings
                $settings['error'] = [
                    // Should be set to false in production
                    'display_error_details' => true,
                    // Parameter is passed to the default ErrorHandler
                    // View in rendered output by enabling the "displayErrorDetails" setting.
                    // For the console and unit tests we also disable it
                    'log_errors' => true,
                    // Display error details in error log
                    'log_error_details' => true,
                ];
                return $settings;
            },
            # Configure Twig templates 
            "twig" => function() {
                $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/../../templates");
                $twig = new \Twig\Environment($loader, [
                    __DIR__ . "/../../var/cache"
                ]);
                return $twig;
            },
            \Slim\App::class => function(\Psr\Container\ContainerInterface $container) {
                # create app instance with DI container 
                \Slim\Factory\AppFactory::setContainer($container);
                $app = \Slim\Factory\AppFactory::create();
                # Configure app with middleware
                $app->addRoutingMiddleware();
                $app->addBodyParsingMiddleware();
                $app->addErrorMiddleware(true, true, true);
                $app->add(\Selective\BasePath\BasePathMiddleware::class);
                $app->add(\Slim\Middleware\ErrorMiddleware::class);
                return $app;
            },
            \Slim\Middleware\ErrorMiddleware::class => function(\Psr\Container\ContainerInterface $container) {
                $app = $container->get(\Slim\App::class);
                $settings = $container->get('settings')['error'];
                return new \Slim\Middleware\ErrorMiddleware(
                        $app->getCallableResolver(),
                        $app->getResponseFactory(),
                        (bool)$settings["display_error_details"],
                        (bool)$settings["log_errors"],
                        (bool)$settings["log_error_details"]
                );
            }
        ]);
        
        # build the container with configured Classes, Objects
        $container = $containerBuilder->build();
        # create app instance via container
        $app = $container->get(\Slim\App::class);
        
        return $app;
    }
    
}
