<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi;

use WebServCo\Api\JsonApi\Error;
use WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface;

class Document implements \WebServCo\Framework\Interfaces\JsonInterface
{
    /**
    * @var array<string,int|string>
    */
    protected array $meta;

    /**
    * @var array<string,string>
    */
    protected array $jsonapi;

    /**
    * @var array<int,ResourceObjectInterface>
    */
    protected array $data;

    /**
    * @var array<int,Error>
    */
    protected array $errors;

    protected int $statusCode;

    const CONTENT_TYPE = 'application/vnd.api+json';
    const VERSION = '1.0';

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
            $dataItems = count($this->data);
            if (1 < $dataItems) {
                foreach ($this->data as $item) {
                    $array['data'][] = $item->toArray();
                }
            } else {
                $array['data'] = $this->data[0]->toArray();
            }
        }
        return $array;
    }

    public function toJson(): string
    {
        $array = $this->toArray();
        return (string) json_encode($array);
    }
}
