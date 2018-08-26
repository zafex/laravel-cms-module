<?php

namespace Apiex\Common;

use Apiex\Methods\ResponseTrait;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
use Illuminate\Http\JsonResponse;

class ResponseError
{
    use ResponseTrait;

    /**
     * @param  Exception $exception
     * @return mixed
     */
    public function sendException(Exception $exception): JsonResponse
    {
        $httpStatusCode = method_exists($exception, 'getStatusCode') ? ($exception->getStatusCode() ?: 404): 500;
        $httpStatusMessage = array_get($this->createMeta($httpStatusCode), 'status_message');

        $errors = [
            [
                "error" => [
                    'resource' => 'exception',
                    'detail' => $exception->getMessage() ?: $httpStatusMessage,
                    "code" => $exception->getCode(),
                ],
            ],
        ];
        return $this->createCollection('errors', $httpStatusCode, $errors, ['http_status' => array_get($this->createMeta($httpStatusCode), 'http_status')]);
    }

    /**
     * @param  $message
     * @param  $status
     * @return mixed
     */
    public function sendMessage($message, $httpStatusCode = 400): JsonResponse
    {
        $errors = [
            [
                "error" => [
                    'resource' => 'unexpected',
                    'detail' => $message,
                ],
            ],
        ];
        return $this->createCollection('errors', $httpStatusCode, $errors, ['http_status' => array_get($this->createMeta($httpStatusCode), 'http_status')]);
    }

    /**
     * @param  ValidatorInterface $validator
     * @param  $resource
     * @param  $status
     * @return mixed
     */
    public function sendValidation(ValidatorInterface $validator, $resource = 'Common', $httpStatusCode = 400): JsonResponse
    {
        $errors = [];
        foreach ($validator->errors()->toArray() as $key => $errors) {
            foreach ($errors as $message) {
                $errors[] = [
                    'error' => [
                        'resource' => $resource,
                        'field' => $key,
                        'detail' => $message,
                    ],
                ];
            }
        }
        return $this->createCollection('errors', $httpStatusCode, $errors, ['http_status' => array_get($this->createMeta($httpStatusCode), 'http_status')]);
    }
}
