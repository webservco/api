<?php declare(strict_types = 1);

namespace WebServCo\Api\JsonApi;

abstract class AbstractResourceObject implements
    \WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface,
    \WebServCo\Framework\Interfaces\JsonInterface
{

    protected string $type;

    protected string $id;

    /**
     * @var array<string,array<string,int|string>|string>
     */
    protected array $attributes;

    /**
     * @var array<string,string>
     */
    protected array $links;

    /**
     * @var array<string,int|string>
     */
    protected array $meta;

    public function __construct()
    {
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
    * @return int|string
    */
    public function getMeta(string $key)
    {
        if (!\array_key_exists($key, $this->meta)) {
            throw new \InvalidArgumentException(\sprintf('Meta not found: %s', $key));
        }
        return $this->meta[$key];
    }

    public function setType(string $type): bool
    {
        $this->type = $type;
        return true;
    }

    public function setId(string $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
    * @param array<string,int|string>|string $value
    */
    public function setAttribute(string $key, $value): bool
    {
        $this->attributes[$key] = $value;
        return true;
    }

    public function setLink(string $key, string $value): bool
    {
        $this->links[$key] = $value;
        return true;
    }

    /**
    * @param int|string $value
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
