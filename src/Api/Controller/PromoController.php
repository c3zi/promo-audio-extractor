<?php

declare(strict_types=1);

namespace Promo\VideoProcessor\Api\Controller;

use Promo\VideoProcessor\Application\Dto\Tag;
use Promo\VideoProcessor\Application\Exception\UnsupportedTagException;
use Promo\VideoProcessor\Application\Service\AudioExtractor;
use Promo\VideoProcessor\Api\Response\ResponseCode;
use Promo\VideoProcessor\Application\Storage\Storage;
use Promo\VideoProcessor\Domain\Exception\DomainException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;

class PromoController extends BaseController
{
    /** @var AudioExtractor */
    private $audioExtractor;
    /** @var Storage */
    private $storage;

    public function __construct(AudioExtractor $audioExtractor, Storage $storage)
    {
        $this->audioExtractor = $audioExtractor;
        $this->storage = $storage;
    }

    public function convert(Request $request, Response $response, array $args): Response
    {
        $queryParams = $request->getQueryParams();
        $tagName = $queryParams['tag'] ?? '';

        try {
            $tag = new Tag($tagName);
        } catch (UnsupportedTagException $exception) {
            $data = [
                'code' => ResponseCode::HTTP_BAD_REQUEST,
                'message' => $exception->getMessage()
            ];

            return $this->json($response, $data, ResponseCode::HTTP_BAD_REQUEST);
        }


        try {
            $audio = $this->audioExtractor->extract($tag);
        } catch (DomainException $exception) {
            $data = [
                'code' => ResponseCode::HTTP_INTERNAL_ERROR,
                'message' => 'Internal error',
            ];

            // @todo the exception should be reported in entry log (i.e. Monolog)

            return $this->json($response, $data, ResponseCode::HTTP_INTERNAL_ERROR);
        }

        $data = [
            'id' => $audio->id(),
            'mp3' => $audio->name(),
        ];

        return $this->json($response, $data, ResponseCode::HTTP_OK);
    }

    public function fetchMp3(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;
        $name = $id . '.mp3';

        if (!$this->storage->exists($name)) {
            $data = [
                'code' => ResponseCode::HTTP_NOT_FOUND,
                'message' => sprintf('Mp3 file (%s) does not exist.', $name),
            ];

            return $this->json($response, $data, ResponseCode::HTTP_NOT_FOUND);
        }

        $file = $this->storage->path($name);
        $fh = fopen($file, 'rb');
        $stream = new Stream($fh);

        return $response->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }
}
