<?php
namespace WebServCo\Api\JsonApi;

class Structure
{
    protected $meta;
    protected $jsonapi;
    protected $data;
    protected $errors;

    const CONTENT_TYPE = 'application/vnd.api+json';
    const VERSION = '1.0';

    public function __construct()
    {
        $this->meta = [];
        $this->jsonapi = ['version' => self::VERSION];
        $this->data = [];
        $this->errors = [];
    }

    public function setData(\WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface $resourceObject)
    {
        $this->data[] = $resourceObject;
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
            $dataItems = count($this->data);
            if (1 < $dataItems) {
                foreach ($this->data as $item) {
                    $array['data'][] = $item->toArray();
                }
            } else {
                $array['data'] = $this->data[0]->toArray();
            }
        }
        return $array;
    }

    public function toJson()
    {
        $array = $this->toArray();
        return json_encode($array);
    }
}
