<?php declare(strict_types = 1);

namespace WebServCo\Api\JsonApi;

class Response extends \WebServCo\Framework\Http\Response
{

    public function __construct(Document $document)
    {
        parent::__construct(
            $document->toJson(),
            $document->getStatusCode(),
            ['Content-Type' => [Document::CONTENT_TYPE]]
        );
    }
}
