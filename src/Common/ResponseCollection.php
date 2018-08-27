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
     * @param  array          $meta
     * @param  array          $headers
     * @param  $options
     * @return mixed
     */
    public function send($collections, $status = 200, array $meta = [], array $headers = [], $options = 0): JsonResponse
    {
        $meta = array_merge($meta, [
            'http_status' => array_get($this->createMeta($status), 'http_status'),
            'logref' => $this->createLogref(),
            'type' => 'collection',
        ]);
        $items = [];
        foreach ($collections as $data) {
            $items[]['data'] = $data;
        }
        return $this->createCollection('items', $status, $items, $meta, $headers, $options);
    }
}
