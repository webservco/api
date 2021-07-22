<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi;

use WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface;

class Document implements \WebServCo\Framework\Interfaces\JsonInterface
{

    public const CONTENT_TYPE = 'application/vnd.api+json';
    public const VERSION = '1.0';

    /**
     * Meta.
     *
     * @var array<string,int|string>
     */
    protected array $meta;

    /**
     * JSON API
     *
     * @var array<string,string>
     */
    protected array $jsonapi;

    /**
    * Data.
    *
    * @var array<int,\WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface>
    */
    protected array $data;

    /**
    * Errors.
    *
    * @var array<int,\WebServCo\Api\JsonApi\Error>
    */
    protected array $errors;

    protected int $statusCode;

    public function __construct()
    {
        $this->meta = [];
        $this->jsonapi = ['version' => self::VERSION];
        $this->data = [];
        $this->errors = [];
        $this->statusCode = 200;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setData(ResourceObjectInterface $resourceObject): bool
    {
        $this->data[] = $resourceObject;
        return true;
    }

    public function setError(Error $error): bool
    {
        $this->errors[] = $error;
        $this->statusCode = $error->getStatus(); // set status code of last error.
        return true;
    }

    public function setStatusCode(int $statusCode): bool
    {
        $this->statusCode = $statusCode;
        return true;
    }

    /**
    * @return array<string,mixed>
    */
    public function toArray(): array
    {
        $array = [
            'jsonapi' => $this->jsonapi,
        ];
        if ($this->meta) {
            $array['meta'] = $this->meta;
        }
        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $array['errors'][] = $error->toArray();
            }
        } else {
            $dataItems = \count($this->data);
            if (1 < $dataItems) { // multiple items
                foreach ($this->data as $item) {
                    $array['data'][] = $item->toArray();
                }
            } else {
                $array['data'] = \array_key_exists(0, $this->data)
                    ? $this->data[0]->toArray() // one item
                    : []; // no data
            }
        }
        return $array;
    }

    public function toJson(int $flags = 0): string
    {
        $array = $this->toArray();
        return (string) \json_encode($array, $flags);
    }
}
