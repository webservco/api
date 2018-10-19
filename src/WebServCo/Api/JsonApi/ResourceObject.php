<?php
namespace WebServCo\Api\JsonApi;

final class ResourceObject
{
    protected $type;
    protected $id;
    protected $attributes;
    protected $meta;

    const TYPE_TEST = 'test';

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
}
