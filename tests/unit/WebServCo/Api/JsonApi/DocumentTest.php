<?php
namespace Tests\Api\JsonApi;

use PHPUnit\Framework\TestCase;
use WebServCo\Api\JsonApi\Document;

final class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function constantContentTypeHasExpectedValue()
    {
        $this->assertEquals('application/vnd.api+json', Document::CONTENT_TYPE);
    }

    /**
     * @test
     */
    public function constantVersionHasExpectedValue()
    {
        $this->assertEquals('1.0', Document::VERSION);
    }
}
