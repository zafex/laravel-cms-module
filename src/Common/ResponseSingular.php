<?php

namespace Apiex\Common;

use Apiex\Methods\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ResponseSingular
{
    use ResponseTrait;

    /**
     * @param $item
     * @param $status
     * @param array      $headers
     * @param $options
     */
    public function send($item, $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        $data = [
            'data' => $item,
            'meta' => [
                'http_status' => array_get($this->createMeta($status), 'http_status'),
                'logref' => $this->createLogref(),
            ],
        ];
        return new JsonResponse($data, $status, $headers, $options);
    }
}
