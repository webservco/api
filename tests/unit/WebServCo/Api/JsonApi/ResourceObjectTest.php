<?php
namespace Tests\Framework;

use PHPUnit\Framework\TestCase;
use WebServCo\Framework\Environment as Env;

final class ResourceObjectTest extends TestCase
{
    /**
     * @test
     */
    public function constantTypeTestHasExpectedValue()
    {
        $this->assertEquals('test', \WebServCo\Api\JsonApi\ResourceObject::TYPE_TEST);
    }
}
