<?php

declare(strict_types=1);

use DI\Container;
use Promo\VideoProcessor\Api\Handlers\HttpErrorHandler;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\RoutingMiddleware;
use Psr\Log\LoggerInterface;

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

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($responseFactory, $container->get(LoggerInterface::class));

// Add Routing Middleware
$routeResolver = $app->getRouteResolver();
$routingMiddleware = new RoutingMiddleware($routeResolver);
$app->add($routingMiddleware);

// Add Error Middleware
$callableResolver = $app->getCallableResolver();
$errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, $displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
$app->add($errorMiddleware);

$app->run();
