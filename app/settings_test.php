<?php
declare(strict_types=1);

use DI\Container;
use Slim\App;

return function (App $app) {
    /** @var Container $container */
    $container = $app->getContainer();

    // Global Settings Object
    $container->set('settings', [
        'storage' => __DIR__ . '/../storage/test',
        'promo-url' => 'http://promo.com/for',
        'displayErrorDetails' => true,
    ]);
};
