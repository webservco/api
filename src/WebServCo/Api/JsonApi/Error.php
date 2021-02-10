<?php declare(strict_types = 1);

namespace WebServCo\Api\JsonApi;

final class Error
{

    protected string $id;

    /**
     * @var array<string,string>
     */
    protected array $links;

    protected int $status;

    protected int $code;

    protected string $title;

    protected string $detail;

    protected string $source;

    /**
     * @var array<string,int|string>
     */
    protected array $meta;

    public function __construct()
    {
        $this->meta = [];
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): bool
    {
        $this->status = $status;
        return true;
    }

    public function setTitle(string $title): bool
    {
        $this->title = $title;
        return true;
    }

    public function setDetail(string $detail): bool
    {
        $this->detail = $detail;
        return true;
    }

    public function setMeta(string $key, string $value): bool
    {
        $this->meta[$key] = $value;
        return true;
    }

    /**
    * @return array<string,mixed>
    */
    public function toArray(): array
    {
        $array = [];
        foreach (\get_object_vars($this) as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $array[$key] = $value;
        }
        return $array;
    }
}
