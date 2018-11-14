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

    public function testItThrowsWhenNonRouteIsPassed()
    {
        $this->expectException(\InvalidArgumentException::class);
        new RouteGroup("/some/path", ["hi"]);
    }

    public function testItReturnsCorrectValues()
    {
        $route1 = $this->createMock(SuperSimpleRouting\Route::class);
        $route2 = $this->createMock(SuperSimpleRouting\Route::class);
        $routes = [$route1,$route2];
        $path = "/some/path";

        $group = new RouteGroup($path, $routes);
        $this->assertEquals($path, $group->getPath());
        $this->assertEquals($routes, $group->getRoutes());
    }
}