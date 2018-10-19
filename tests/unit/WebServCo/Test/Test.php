<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

final class Test extends TestCase
{
    /**
     * @test
     */
    public function dummyPassingTest()
    {
        $this->assertTrue(true);
    }
    
    public function blank()
    {
        $this->markTestIncomplete('TODO');
    }
}
