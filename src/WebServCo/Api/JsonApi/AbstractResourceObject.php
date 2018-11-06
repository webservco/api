<?php
namespace WebServCo\Api\JsonApi;

abstract class AbstractResourceObject implements \WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface
{
    protected $type;
    protected $id;
    protected $attributes;
    protected $links;
    protected $meta;

    public function __construct()
    {
        $this->attributes = [];
        $this->links = [];
        $this->meta = [];
    }

    public function getAttribute($key)
    {
        if (!array_key_exists($key, $this->attributes)) {
            throw new \InvalidArgumentException(sprintf('Attribute not found: %s', $key));
        }
        return $this->attributes[$key];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMeta($key)
    {
        if (!array_key_exists($key, $this->meta)) {
            throw new \InvalidArgumentException(sprintf('Meta not found: %s', $key));
        }
        return $this->meta[$key];
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function setLink($key, $value)
    {
        $this->links[$key] = $value;
    }

    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }

    public function toArray()
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

    public function toJson()
    {
        $array = $this->toArray();
        return json_encode($array);
    }
}
