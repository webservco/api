<?php
namespace WebServCo\Api\JsonApi\Interfaces;

interface ResourceObjectInterface
{
    public function getId();
    public function setType($type);
    public function setId($id);
    public function setAttribute($key, $value);
    public function setLink($key, $value);
    public function setMeta($key, $value);
    public function toArray();
    public function toJson();
}
