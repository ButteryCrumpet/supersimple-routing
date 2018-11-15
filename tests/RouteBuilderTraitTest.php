<?php

use PHPUnit\Framework\TestCase;

class RouteBuilderTraitTest extends TestCase
{
    public function testCanAddRoute()
    {
        $mock = $this->getMockForTrait(\SuperSimpleRouting\RouteBuilderTrait::class);
        $mock->route(["GET"],"path", "handler");
        $routes = $mock->getRoutes();
        $this->assertInstanceOf(\SuperSimpleRouting\Route::class, $routes[0]);
        $this->assertEquals("path", $routes[0]->getPath());
        $this->assertEquals("handler", $routes[0]->getHandler());
    }

    public function testCanAddGroup()
    {
        $mock = $this->getMockForTrait(\SuperSimpleRouting\RouteBuilderTrait::class);
        $mock->group("path", function(){});
        $routes = $mock->getRoutes();
        $this->assertInstanceOf(\SuperSimpleRouting\RouteGroup::class, $routes[0]);
        $this->assertEquals("path", $routes[0]->getPath());
    }

    public function testCanAddGroupedRoute()
    {
        $mock = $this->getMockForTrait(\SuperSimpleRouting\RouteBuilderTrait::class);
        $mock->group("path", function($group){
            $group->route(["GET"],"path", "handler");
        });
        $routes = $mock->getRoutes()[0]->getRoutes();
        $this->assertInstanceOf(\SuperSimpleRouting\Route::class, $routes[0]);
        $this->assertEquals("path", $routes[0]->getPath());
    }

    public function testCanAddNestedGroup()
    {
        $mock = $this->getMockForTrait(\SuperSimpleRouting\RouteBuilderTrait::class);
        $mock->group("path", function($group){
            $group->group("/more", function() {
            });
        });
        $routes = $mock->getRoutes()[0]->getRoutes();
        $this->assertInstanceOf(\SuperSimpleRouting\RouteGroup::class, $routes[0]);
        $this->assertEquals("/more", $routes[0]->getPath());
    }

    public function testCanAddNestedGroupedRoute()
    {
        $mock = $this->getMockForTrait(\SuperSimpleRouting\RouteBuilderTrait::class);
        $mock->group("path", function($group){
            $group->group("/more", function($group) {
                $group->route(["GET"],"/final", "handler");
            });
        });
        $routes = $mock->getRoutes()[0]->getRoutes()[0]->getRoutes();
        $this->assertInstanceOf(\SuperSimpleRouting\Route::class, $routes[0]);
        $this->assertEquals("/final", $routes[0]->getPath());
    }
}