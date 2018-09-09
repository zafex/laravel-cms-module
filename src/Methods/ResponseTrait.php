<?php

namespace Apiex\Methods;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

trait ResponseTrait
{
    /**
     * @var array
     */
    protected $collections = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var mixed
     */
    protected $item = null;

    /**
     * @var array
     */
    protected $metaNames = [];

    /**
     * @param $header
     */
    public function addHeader($header)
    {
        array_push($this->headers, $header);
        return $this;
    }

    /**
     * @return mixed
     */
    public function fetchMeta(): array
    {
        return $this->metaNames ?: [];
    }

    /**
     * @return mixed
     */
    public function getHeaders(): array
    {
        return $this->headers ?: [];
    }

    /**
     * @param $meta
     */
    public function withMeta($meta)
    {
        $this->metaNames = array_merge($this->metaNames, $meta);
        return $this;
    }

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

    protected function resetVars()
    {
        $this->collections = [];
        $this->metaNames = [];
        $this->headers = [];
        $this->item = null;
    }
}
