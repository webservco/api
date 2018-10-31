<?php
namespace WebServCo\Api\JsonApi;

class Response extends \WebServCo\Framework\HttpResponse
{
    public function __construct(
        Document $document
    ) {
        parent::__construct(
            $document->toJson(),
            $document->getStatusCode(),
            ['Content-Type' => Document::CONTENT_TYPE]
        );
    }
}
