<?php

declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\RoutingMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI Container
$container = new Container();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($app);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// Add Routing Middleware
$routeResolver = $app->getRouteResolver();
$routingMiddleware = new RoutingMiddleware($routeResolver);
$app->add($routingMiddleware);

$app->run();
