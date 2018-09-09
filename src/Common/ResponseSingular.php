<?php

namespace Apiex\Common;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Methods\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ResponseSingular
{
    use ResponseTrait;

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param $httpStatusCode
     */
    public function send($httpStatusCode = 200): JsonResponse
    {
        $this->withMeta([
            'http_status' => array_get($this->createMeta($httpStatusCode), 'http_status'),
            'logref' => $this->createLogref(),
        ]);
        $data = [
            'data' => $this->getItem(),
            'meta' => $this->fetchMeta(),
        ];
        $headers = $this->getHeaders();
        $this->resetVars();
        return new JsonResponse($data, $httpStatusCode, $headers);
    }

    /**
     * @param $item
     */
    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }
}
