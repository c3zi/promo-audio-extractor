<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Tests\Api\Controller;

use DI\Container;
use Promo\VideoProcessor\Api\Response\ResponseCode;
use Promo\VideoProcessor\Application\Storage\Storage;
use Promo\VideoProcessor\Infrastructure\Storage\Filesystem;
use Promo\VideoProcessor\Tests\TestCase;
use function realpath;
use function json_decode;

final class PromoControllerTest extends TestCase
{
    public function testWhenTagDoesNotExist(): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/api/promo2mp3');

        $response = $app->handle($request);
        $payload = json_decode((string) $response->getBody());

        $this->assertEquals(ResponseCode::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('Given tag () is not supported.', $payload->message);
        $this->assertEquals(ResponseCode::HTTP_BAD_REQUEST, $payload->code);
    }

    public function testWhenTagIsNotSupported(): void
    {
        $app = $this->getAppInstance();
        $request = $this
            ->createRequest('GET', '/api/promo2mp3')
            ->withQueryParams(['tag' => 'unsupported']);

        $response = $app->handle($request);
        $payload = json_decode((string) $response->getBody());

        $this->assertEquals(ResponseCode::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('Given tag (unsupported) is not supported.', $payload->message);
        $this->assertEquals(ResponseCode::HTTP_BAD_REQUEST, $payload->code);
    }

    public function testWhenTagIsSupported(): void
    {
        $app = $this->getAppInstance();

        $request = $this
            ->createRequest('GET', '/api/promo2mp3')
            ->withQueryParams(['tag' => 'gaming']);

        $response = $app->handle($request);
        $payload = json_decode((string) $response->getBody());

        $this->assertNotNull($payload->id);
        $this->assertNotNull($payload->mp3);
    }

    public function testFetchMp3WhenFileExists(): void
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $basePath = realpath(__DIR__.'/../../data');
        $container->set(Storage::class, new Filesystem($basePath));

        $request = $this->createRequest('GET', '/api/mp3/5c1f3b31374e2772197b23cb');


        $response = $app->handle($request);
        $headers = $response->getHeaders();

        $this->assertEquals(ResponseCode::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($headers['Content-Disposition'][0], 'attachment; filename="5c1f3b31374e2772197b23cb.mp3"');
    }

    public function testFetchMp3WhenFileDoesNotExist(): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/api/mp3/1111111111111111111');
        $response = $app->handle($request);

        $this->assertEquals(ResponseCode::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
