<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Api\Controller;

use function json_encode;
use Slim\Psr7\Response;

class BaseController
{
    public function json(Response $response, array $data = [], int $statusCode = 0): Response
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
