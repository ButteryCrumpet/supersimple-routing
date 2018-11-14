<?php

use PHPUnit\Framework\TestCase;

class MiddlewareAwareTraitTest extends TestCase
{
    public function testSetsAndGetsMiddleware()
    {
        $mock = $this->getMockForTrait(\SuperSimpleRouting\MiddlewareAwareTrait::class);
        $mock->with(["hi", "ho"]);
        $this->assertEquals(
            ["hi", "ho"],
            $mock->getMiddleware()
        );
    }
}