<?php
declare(strict_types=1);

use DI\Container;
use Slim\App;
use Monolog\Logger;

return function (App $app) {
    /** @var Container $container */
    $container = $app->getContainer();

    // Global Settings Object
    $container->set('settings', [
        'storage' => __DIR__ . '/../storage',
        'promo-url' => 'http://promo.com/for',
        'displayErrorDetails' => true, // Should be set to false in production
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => Logger::DEBUG,
        ],
    ]);
};
