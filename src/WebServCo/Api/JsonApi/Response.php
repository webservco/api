<?php
namespace WebServCo\Api\JsonApi;

class Response extends \WebServCo\Framework\HttpResponse
{
    public function __construct(
        \WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface $resourceObject,
        $errors = [],
        $statusCode = 200
    ) {
        $structure = new Structure();
        $structure->setData($resourceObject);
        foreach ($errors as $error) {
            if ($error instanceof \WebServCo\Api\JsonApi\Error) {
                $structure->setError($error);
            }
        }

        parent::__construct($this->getOutput($structure), $statusCode, ['Content-Type' => Structure::CONTENT_TYPE]);
    }

    protected function getOutput(Structure $structure)
    {
        $jsonOutput = new \WebServCo\Framework\Libraries\JsonOutput();
        $array = $structure->toArray();
        foreach ($array as $key => $value) {
            $jsonOutput->setData($key, $value);
        }
        return $jsonOutput->render();
    }
}
