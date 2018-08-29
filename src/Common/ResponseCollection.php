<?php

namespace Apiex\Common;

use Apiex\Methods\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ResponseCollection
{
    use ResponseTrait;

    /**
     * @param  $item
     * @return mixed
     */
    public function addCollection($item)
    {
        array_push($this->collections, $item);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCollections(): array
    {
        return $this->collections ?: [];
    }

    /**
     * @param  $httpStatusCode
     * @return mixed
     */
    public function send($httpStatusCode = 200): JsonResponse
    {
        $this->withMeta([
            'http_status' => array_get($this->createMeta($httpStatusCode), 'http_status'),
            'logref' => $this->createLogref(),
            'type' => 'collection',
        ]);
        $collections = [];
        foreach ($this->getCollections() as $data) {
            $collections[]['data'] = $data;
        }
        $meta = $this->fetchMeta();
        $headers = $this->getHeaders();
        $this->resetVars();
        return $this->createCollection('items', $httpStatusCode, $collections, $meta, $headers);
    }
}
