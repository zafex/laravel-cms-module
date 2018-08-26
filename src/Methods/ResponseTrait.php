<?php

namespace Apiex\Methods;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

trait ResponseTrait
{
    /**
     * @param $key
     * @param $status
     * @param array      $meta
     * @param array      $collections
     * @param array      $headers
     * @param $options
     */
    protected function createCollection($key, $status, array $collections, array $meta = [], array $headers = [], $options = 0): JsonResponse
    {
        $data = [
            $key => $collections,
            'meta' => $meta,
        ];
        return new JsonResponse($data, $status, $headers, $options);
    }

    protected function createLogref()
    {
        try {
            return Uuid::uuid4()->toString();
        } catch (UnsatisfiedDependencyException $e) {
            return uniqid();
        }
    }

    /**
     * @param  $httpStatusCode
     * @param  $only
     * @return mixed
     */
    protected function createMeta($httpStatusCode = 200)
    {
        $httpStatusMessage = array_key_exists($httpStatusCode, JsonResponse::$statusTexts) ? JsonResponse::$statusTexts[$httpStatusCode] : 'An error occurred';
        $results = [
            'status_code' => $httpStatusCode,
            'status_message' => $httpStatusMessage,
            'http_status' => sprintf('%d %s', $httpStatusCode, $httpStatusMessage),
        ];
        return $results;
    }
}
