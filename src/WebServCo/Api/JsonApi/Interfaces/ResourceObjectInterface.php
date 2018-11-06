<?php
namespace WebServCo\Api\JsonApi\Interfaces;

interface ResourceObjectInterface
{
    public function getAttribute($key);
    public function getId();
    public function getMeta($key);
    public function setType($type);
    public function setId($id);
    public function setAttribute($key, $value);
    public function setLink($key, $value);
    public function setMeta($key, $value);
    public function toArray();
    public function toJson();
}
