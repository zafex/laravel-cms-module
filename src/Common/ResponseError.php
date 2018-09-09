<?php

namespace Apiex\Common;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Methods\ResponseTrait;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;

class ResponseError
{
    use ResponseTrait;

    /**
     * @param  $httpStatusCode
     * @return mixed
     */
    public function send($httpStatusCode = 400)
    {
        $this->withMeta([
            'http_status' => array_get($this->createMeta($httpStatusCode), 'http_status'),
            'logref' => $this->createLogref(),
        ]);
        $collections = [];
        foreach ($this->collections as $data) {
            $collections[] = $data;
        }
        $meta = $this->fetchMeta();
        $headers = $this->getHeaders();
        $this->resetVars();
        return $this->createCollection('errors', $httpStatusCode, $collections, $meta, $headers);
    }

    /**
     * @param  Exception $exception
     * @return mixed
     */
    public function withException(Exception $exception): ResponseError
    {
        $httpStatusCode = method_exists($exception, 'getStatusCode') ? ($exception->getStatusCode() ?: 404): 500;
        $httpStatusMessage = array_get($this->createMeta($httpStatusCode), 'status_message');

        array_push($this->collections, [
            "error" => [
                'resource' => get_class($exception),
                'detail' => $exception->getMessage() ?: $httpStatusMessage,
                "code" => $exception->getCode(),
            ],
        ]);
        return $this;
    }

    /**
     * @param  $message
     * @return mixed
     */
    public function withMessage($message, $resource = 'unexpected'): ResponseError
    {
        array_push($this->collections, [
            "error" => [
                'resource' => $resource,
                'detail' => $message,
            ],
        ]);
        return $this;
    }

    /**
     * @param  ValidatorInterface $validator
     * @param  $resource
     * @return mixed
     */
    public function withValidation(ValidatorInterface $validator, $resource = 'Common'): ResponseError
    {
        foreach ($validator->errors()->toArray() as $key => $messages) {
            foreach ($messages as $message) {
                array_push($this->collections, [
                    'error' => [
                        'resource' => $resource,
                        'field' => $key,
                        'detail' => $message,
                    ],
                ]);
            }
        }
        return $this;
    }
}
