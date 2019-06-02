<?php
declare(strict_types=1);

namespace Promo\VideoProcessor\Api\Handlers;

use Exception;
use Promo\VideoProcessor\Api\Response\ResponseCode;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(ResponseFactoryInterface $responseFactory, LoggerInterface $logger)
    {
        parent::__construct($responseFactory);
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = ResponseCode::HTTP_INTERNAL_ERROR;
        $error = [
            'status' => ResponseCode::HTTP_INTERNAL_ERROR,
            'message' => 'An internal error has occurred while processing your request.',
        ];

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $message = $exception->getMessage();
            $error = [
                'message' => $message,
                'status' => $statusCode,
            ];
        }

        if (!($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $this->logger->error($exception->getMessage(), ['trace' => $exception->getTraceAsString()]);
        }

        $encodedPayload = json_encode($error, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write($encodedPayload);

        return $response;
    }
}
