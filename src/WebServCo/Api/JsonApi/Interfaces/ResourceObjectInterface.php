<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi\Interfaces;

interface ResourceObjectInterface
{

    /**
    * @return array<string,int|string>|string
    */
    public function getAttribute(string $key);

    public function getId(): string;

    /**
    * @return int|string
    */
    public function getMeta(string $key);

    public function setId(string $id): bool;

    /**
    * @param array<string,int|string>|string $value
    */
    public function setAttribute(string $key, $value): bool;

    public function setLink(string $key, string $value): bool;

    /**
    * @param int|string $value
    */
    public function setMeta(string $key, $value): bool;

    /**
    * @return array<string,mixed>
    */
    public function toArray(): array;

    public function toJson(): string;
}
