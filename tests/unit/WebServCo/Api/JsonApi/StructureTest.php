<?php
namespace Tests\Api\JsonApi;

use PHPUnit\Framework\TestCase;

final class StructureTest extends TestCase
{
    /**
     * @test
     */
    public function constantContentTypeHasExpectedValue()
    {
        $this->assertEquals('application/vnd.api+json', \WebServCo\Api\JsonApi\Structure::CONTENT_TYPE);
    }
}
