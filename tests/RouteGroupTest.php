<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleRouting\RouteGroup;

class RouteGroupTest extends TestCase
{
    public function testItInitializes()
    {
        $route1 = $this->createMock(SuperSimpleRouting\Route::class);
        $route2 = $this->createMock(SuperSimpleRouting\Route::class);
        $childGroup = new RouteGroup("/", [$route2]);

        $group = new RouteGroup("/some/path", [$route1, $childGroup]);
        $this->assertInstanceOf(RouteGroup::class, $group);
    }

    public function testItReturnsCorrectValues()
    {
        $path = "/some/path";
        $group = new RouteGroup($path);

        $group->get("/next", "handler");
        $this->assertEquals($path, $group->getPath());
        $this->assertEquals("/next", $group->getRoutes()[0]->getPath());
        $this->assertEquals("handler", $group->getRoutes()[0]->getHandler());
    }
}