<?php
declare(strict_types=1);

use Promo\VideoProcessor\Domain\Service\AudioProcessor;
use Promo\VideoProcessor\Domain\Service\VideoDownloader;
use Promo\VideoProcessor\Domain\Service\VideoFinder;
use Promo\VideoProcessor\Application\Service\AudioExtractor;
use Promo\VideoProcessor\Application\Storage\Storage;
use Promo\VideoProcessor\Infrastructure\Service\Mp3AudioProcessor;
use Promo\VideoProcessor\Infrastructure\Service\SimpleVideoDownloader;
use Promo\VideoProcessor\Infrastructure\Service\CrawlVideoFinder;
use Promo\VideoProcessor\Infrastructure\Storage\Filesystem;
use Promo\VideoProcessor\Api\Controller\PromoController;
use DI\Container;
use Slim\App;

return static function (App $app) {
    /** @var Container $container */
    $container = $app->getContainer();

    $container->set(Storage::class, static function (Container $c) {
        $settings = $c->get('settings');

        return new Filesystem($settings['storage']);
    });

    $container->set(AudioProcessor::class, static function (Container $c) {
        return new Mp3AudioProcessor($c->get(Storage::class));
    });

    $container->set(VideoDownloader::class, static function (Container $c) {
        return new SimpleVideoDownloader($c->get(Storage::class));
    });

    $container->set(VideoFinder::class, static function (Container $c) {
        $settings = $c->get('settings');

        return new CrawlVideoFinder($settings['promo-url']);
    });

    $container->set(AudioExtractor::class, static function (Container $c) {
        return new AudioExtractor(
            $c->get(VideoFinder::class),
            $c->get(VideoDownloader::class),
            $c->get(AudioProcessor::class)
        );
    });



    $container->set(PromoController::class, static function (Container $c) {
        return new PromoController($c->get(AudioExtractor::class), $c->get(Storage::class));
    });

};
