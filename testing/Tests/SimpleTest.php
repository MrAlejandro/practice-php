<?php

namespace Acme\Tests;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testIsTrue()
    {
        $myvar = true;
        $this->assertTrue($myvar);
    }
}
