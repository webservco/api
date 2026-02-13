<?php

declare(strict_types=1);

namespace WebServCo\Api\JsonApi;

use WebServCo\Framework\Http\Response as HttpResponse;

final class Response extends HttpResponse
{
    public function __construct(Document $document)
    {
        parent::__construct(
            $document->toJson(),
            $document->getStatusCode(),
            ['Content-Type' => [Document::CONTENT_TYPE]],
        );
    }
}
