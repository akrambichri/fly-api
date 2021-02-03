<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected int $statusCode = Response::HTTP_OK;

    public const YES = true;
    public const NO = false;
    public const NO_ERRORS_KEY = 'success';
    public const MAIN_MESSAGE_KEY = 'message';
    public const MAIN_RESPONSE_KEY = 'data';
    public const STATUS_CODE_KEY = 'status_code';

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondWithSuccess(string $message, array $data = [], array $inlineData = [], array $headers = [], array $messageData = []): Response
    {
        return $this->respond(static::YES, $message, $data, $inlineData, $headers, $messageData);
    }

    public function respondWithError(string $message, array $data = [], array $inlineData = [], array $headers = [], array $messageData = []): Response
    {
        return $this->respond(static::NO, $message, $data, $inlineData, $headers, $messageData);
    }

    private function respond(bool $hasErrors, string $message, array $data = [], array $inlineData = [], array $headers = [], array $messageData = []): Response
    {
        $content = [
            static::NO_ERRORS_KEY => $hasErrors,
            static::MAIN_MESSAGE_KEY => $message,
            static::STATUS_CODE_KEY => $this->getStatusCode(),
        ];

        if (!empty($data)) {
            $content = array_merge($content, [static::MAIN_RESPONSE_KEY => $data]);
        }

        if (!empty($inlineData)) {
            $content = array_merge($content, $inlineData);
        }

        return response($content, $this->getStatusCode(), $headers);
    }
}
