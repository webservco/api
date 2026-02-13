<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi;

use WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface;
use WebServCo\Framework\Interfaces\JsonInterface;

use function array_key_exists;
use function json_encode;

final class Document implements JsonInterface
{
    public const string CONTENT_TYPE = 'application/vnd.api+json';
    public const string VERSION = '1.0';

    /**
     * Meta.
     *
     * @var array<string,mixed>
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

    /**
     * https://jsonapi.org/format/#document-top-level
     *
     * Primary data MUST be either:
     * - a single resource object, a single resource identifier object, or null,
     * for requests that target single resources
     * - an array of resource objects, an array of resource identifier objects, or an empty array ([]),
     * for requests that target resource collections
     */
    public function __construct(protected bool $useDataItemCollection = false)
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
        // set status code of last error.
        $this->statusCode = $error->getStatus();

        return true;
    }

    public function setMeta(string $key, mixed $value): bool
    {
        $this->meta[$key] = $value;

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
        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $array['errors'][] = $error->toArray();
            }
        } else {
            if ($this->useDataItemCollection) {
                $array['data'] = [];
                foreach ($this->data as $item) {
                    $array['data'][] = $item->toArray();
                }
            } else {
                $array['data'] = array_key_exists(0, $this->data)
                    // one item
                    ? $this->data[0]->toArray()
                    // no data
                    : null;
            }
        }
        if ($this->meta) {
            $array['meta'] = $this->meta;
        }

        return $array;
    }

    public function toJson(int $flags = 0): string
    {
        $array = $this->toArray();

        return (string) json_encode($array, $flags);
    }
}
