<?php

declare(strict_types=1);

namespace Tests\Unit\WebServCo\Test;

use PHPUnit\Framework\Attributes\Test as TestAttribute;
use PHPUnit\Framework\TestCase;

final class Test extends TestCase
{
    #[TestAttribute]
    public function dummyPassingTest(): void
    {
        $this->assertTrue(true);
    }

    public function blank(): void
    {
        $this->markTestIncomplete('TODO');
    }
}
