<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi;

use InvalidArgumentException;
use WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface;
use WebServCo\Framework\Interfaces\JsonInterface;

use function array_key_exists;
use function json_encode;
use function sprintf;

abstract class AbstractResourceObject implements
    ResourceObjectInterface,
    JsonInterface
{
    protected string $id;

    /**
     * Attributes.
     *
     * @var array<string,array<string,int|string>|string>
     */
    protected array $attributes;

    /**
     * Links.
     *
     * @var array<string,string>
     */
    protected array $links;

    /**
     * Meta.
     *
     * @var array<string,mixed>
     */
    protected array $meta;

    public function __construct(protected string $type)
    {
        // id must be string, and can be ommited (for example when creating a new resource)
        $this->id = '';
        $this->attributes = [];
        $this->links = [];
        $this->meta = [];
    }

    /**
     * @return array<mixed>|string
     */
    public function getAttribute(string $key): array|string
    {
        if (!array_key_exists($key, $this->attributes)) {
            throw new InvalidArgumentException(sprintf('Attribute not found: %s', $key));
        }

        return $this->attributes[$key];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMeta(string $key): int|string
    {
        if (!array_key_exists($key, $this->meta)) {
            throw new InvalidArgumentException(sprintf('Meta not found: %s', $key));
        }

        return $this->meta[$key];
    }

    public function setAttribute(string $key, mixed $value): bool
    {
        $this->attributes[$key] = $value;

        return true;
    }

    public function setId(string $id): bool
    {
        $this->id = $id;

        return true;
    }

    public function setLink(string $key, string $value): bool
    {
        $this->links[$key] = $value;

        return true;
    }

    public function setMeta(string $key, mixed $value): bool
    {
        $this->meta[$key] = $value;

        return true;
    }

    /**
    * @return array<string,mixed>
    */
    public function toArray(): array
    {
        // phpcs:ignore SlevomatCodingStandard.Arrays.AlphabeticallySortedByKeys.IncorrectKeyOrder
        $array = [
            'type' => $this->type,
            'id' => $this->id,
        ];
        if ($this->attributes) {
            $array['attributes'] = $this->attributes;
        }
        if (!empty($this->links)) {
            $array['links'] = $this->links;
        }
        if (!empty($this->meta)) {
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
