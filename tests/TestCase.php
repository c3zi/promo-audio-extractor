<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests;

use DI\Container;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;
use const DIRECTORY_SEPARATOR;
use function is_dir;
use function fopen;
use function unlink;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getAppInstance(): App
    {
        // Instantiate PHP-DI Container
        $container = new Container();

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Set up settings
        $settings = require __DIR__ . '/../app/settings_test.php';
        $settings($app);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../app/dependencies.php';
        $dependencies($app);

        // Register routes
        $routes = require __DIR__ . '/../app/routes.php';
        $routes($app);

        $s = $container->get('settings');
        $path = $s['storage'];

        if (is_dir($path)) {
            $this->removeFilesFromDirectory($path);
        }

        return $app;
    }

    private function removeFilesFromDirectory(string $directory): void
    {
        $files = glob($directory.DIRECTORY_SEPARATOR.'*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $serverParams = [],
        array $cookies = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $headers = Headers::createFromGlobals($headers);
        $handle = fopen('php://temp', 'wb+');
        $stream = new Stream($handle);

        return new SlimRequest($method, $uri, $headers, $serverParams, $cookies, $stream);
    }
}
