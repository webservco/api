<?php

declare(strict_types=1);

namespace Tests\Test;

use PHPUnit\Framework\TestCase;

final class Test extends TestCase
{
    /**
     * @test
     */
    public function dummyPassingTest(): void
    {
        $this->assertTrue(true);
    }

    public function blank(): void
    {
        $this->markTestIncomplete('TODO');
    }
}
