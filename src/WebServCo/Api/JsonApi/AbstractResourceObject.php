<?php
namespace WebServCo\Api\JsonApi;

abstract class AbstractResourceObject implements \WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface
{
    protected $type;
    protected $id;
    protected $attributes;
    protected $meta;

    public function __construct()
    {
        $this->attributes = [];
        $this->meta = [];
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
        if ($this->meta) {
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
