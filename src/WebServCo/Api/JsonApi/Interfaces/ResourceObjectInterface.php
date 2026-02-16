<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi\Interfaces;

interface ResourceObjectInterface
{
    /**
    * @return array<string,int|string>|string|null
    */
    public function getAttribute(string $key): array|string|null;

    public function getId(): string;

    public function getMeta(string $key): int|string;

    public function setId(string $id): bool;

    public function setAttribute(string $key, mixed $value): bool;

    public function setLink(string $key, string $value): bool;

    public function setMeta(string $key, mixed $value): bool;

    /**
    * @return array<string,mixed>
    */
    public function toArray(): array;

    public function toJson(int $flags = 0): string;
}
