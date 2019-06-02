<?php
declare(strict_types=1);

use Promo\VideoProcessor\Api\Controller\PromoController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/api', function (Group $group) {
        $group->get('/promo2mp3', PromoController::class . ':convert');
        $group->get('/mp3/{id}', PromoController::class . ':fetchMp3');
    });
};
