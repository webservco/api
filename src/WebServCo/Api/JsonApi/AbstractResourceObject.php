<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi;

abstract class AbstractResourceObject implements
    \WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface,
    \WebServCo\Framework\Interfaces\JsonInterface
{

    protected string $id;

    protected string $type;

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
     * @var array<string,int|string|null>
     */
    protected array $meta;

    public function __construct(string $type)
    {
        $this->id = ''; // id must be string, and can be ommited (for example when creating a new resource)
        $this->type = $type;
        $this->attributes = [];
        $this->links = [];
        $this->meta = [];
    }

    /**
    * @return array<string,int|string>|string
    */
    public function getAttribute(string $key)
    {
        if (!\array_key_exists($key, $this->attributes)) {
            throw new \InvalidArgumentException(\sprintf('Attribute not found: %s', $key));
        }
        return $this->attributes[$key];
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
    * @return int|string|null
    */
    public function getMeta(string $key)
    {
        if (!\array_key_exists($key, $this->meta)) {
            throw new \InvalidArgumentException(\sprintf('Meta not found: %s', $key));
        }
        return $this->meta[$key];
    }

    /**
    * @param mixed $value
    */
    public function setAttribute(string $key, $value): bool
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

    /**
    * @param int|string|null $value
    */
    public function setMeta(string $key, $value): bool
    {
        $this->meta[$key] = $value;
        return true;
    }

    /**
    * @return array<string,mixed>
    */
    public function toArray(): array
    {
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

    public function toJson(): string
    {
        $array = $this->toArray();
        return (string) \json_encode($array);
    }
}
