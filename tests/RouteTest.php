<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleRouting\Route;

class RouteTest extends TestCase
{
    public function testItInitializesWithStringMethod()
    {
        $this->assertInstanceOf(
          Route::class,
            new Route("GET", "/some/path", "MAYBE\A\Class")
        );
    }

    public function testItInitializesWithArrayMethod()
    {
        $this->assertInstanceOf(
            Route::class,
            new Route(["GET", "POST"], "/some/path", "Maybe\A\Class")
        );
    }

    public function testReturnsCorrectValues()
    {
        $methods = ["GET", "POST"];
        $path = "/some/path";
        $controller = "Maybe\A\Class";

        $route = new Route($methods, $path, $controller);
        $this->assertEquals($methods, $route->getMethods());
        $this->assertEquals($path, $route->getPath());
        $this->assertEquals($controller, $route->getHandler());
    }
}