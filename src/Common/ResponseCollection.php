<?php

namespace Apiex\Common;

use Apiex\Methods\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ResponseCollection
{
    use ResponseTrait;

    /**
     * @param  $collections
     * @param  $status
     * @param  array          $headers
     * @param  $options
     * @return mixed
     */
    public function send($collections, $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        $meta = [
            'http_status' => array_get($this->createMeta($status), 'http_status'),
            'logref' => $this->createLogref(),
        ];
        return $this->createCollection('items', $status, $collections, $meta, $headers, $options);
    }
}
