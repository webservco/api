<?php
namespace WebServCo\Api\JsonApi\Interfaces;

interface ResourceObjectInterface
{
    /**
    * @param string $key
    * @return array<string,int|string>|string
    */
    public function getAttribute(string $key);

    public function getId(): string;

    /**
    * @param string $key
    * @return int|string
    */
    public function getMeta(string $key);

    public function setType(string $type): bool;

    public function setId(string $id): bool;

    /**
    * @param string $key
    * @param array<string,int|string>|string $value
    * @return bool
    */
    public function setAttribute(string $key, $value): bool;

    public function setLink(string $key, string $value): bool;

    /**
    * @param string $key
    * @param int|string $value
    */
    public function setMeta(string $key, $value): bool;

    /**
    * @return array<string,mixed>
    */
    public function toArray(): array;

    public function toJson(): string;
}
