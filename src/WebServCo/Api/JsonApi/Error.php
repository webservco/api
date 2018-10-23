<?php
namespace WebServCo\Api\JsonApi;

final class Error
{
    protected $id;
    protected $links;
    protected $status;
    protected $code;
    protected $title;
    protected $detail;
    protected $source;
    protected $meta;

    public function __construct()
    {
        $this->meta = [];
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }

    public function toArray()
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) {
            if (!empty($value)) {
                $array[$key] = $value;
            }
        }
        return $array;
    }
}
