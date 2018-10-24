<?php
namespace WebServCo\Api\JsonApi;

class Structure
{
    protected $meta;
    protected $jsonapi;
    protected $data;
    protected $errors;

    const CONTENT_TYPE = 'application/vnd.api+json';

    public function __construct()
    {
        $this->meta = [];
        $this->jsonapi = ['version' => '1.0'];
        $this->data = [];
        $this->errors = [];
    }

    public function setData(\WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface $resourceObject)
    {
        $this->data = $resourceObject->toArray();
    }

    public function setError(\WebServCo\Api\JsonApi\Error $error)
    {
        $this->errors[] = $error;
    }

    public function toArray()
    {
        $array = [
            'jsonapi' => $this->jsonapi,
        ];
        if ($this->meta) {
            $array['meta'] = $this->meta;
        }
        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $array['errors'][] = $error->toArray();
            }
        } else {
            $array['data'] = $this->data;
        }
        return $array;
    }
}
