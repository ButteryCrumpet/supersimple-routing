<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleRouting\Router;

class RouterTest extends TestCase
{
    private $routes;
    private $notFoundAllowed;

    public function setUp()
    {
        $route1 = $this->createRoute("GET", "/", "Cont1", []);
        $route2 = $this->createRoute("POST", "/create", "Cont2", ["1", "2"]);
        $route3 = $this->createRoute("GET", "/top", "Cont3", ["1"]);

        $group1 = $this->createGroup("/{id}", [$route3], []);
        $group2 = $this->createGroup("/posts", [$route2, $group1], ["m1"]);

        $this->routes = [$route1, $group2];
        $this->notFoundAllowed = $this->createRoute("GET", "path", "controller", []);
    }

    public function testItInitializes()
    {
        $handlerFact = $this->createMock(SuperSimpleRouting\HandlerFactoryInterface::class);
        $handlerFact
            ->method("make")
            ->willReturn($this->createMock(Psr\Http\Server\RequestHandlerInterface::class));

        $this->assertInstanceOf(
            Router::class,
            new Router($this->routes, $handlerFact, $this->notFoundAllowed, $this->notFoundAllowed)
        );
    }

    public function testItRun()
    {
        $uri = $this->createMock(Psr\Http\Message\UriInterface::class);
        $uri->method("getPath")->willReturn("/");

        $handlerFact = $this->createMock(SuperSimpleRouting\HandlerFactoryInterface::class);
        $handlerFact
            ->method("make")
            ->willReturn($this->createMock(Psr\Http\Server\RequestHandlerInterface::class));

        $router = new Router($this->routes, $handlerFact, $this->notFoundAllowed, $this->notFoundAllowed);
        $this->assertInstanceOf(
            \Psr\Http\Server\RequestHandlerInterface::class,
            $router->getHandler("GET", $uri)
        );
    }

    public function testPassesCorrectDataToHandlerFactory()
    {
        $uri = $this->createMock(Psr\Http\Message\UriInterface::class);
        $uri->method("getPath")->willReturn("/posts/5/top");
        $handlerFact = $this->createHandlerFactory("Cont3", [ "id" => "5"], ["1", "m1", "r1"]);
        $router = new Router($this->routes, $handlerFact, $this->notFoundAllowed, $this->notFoundAllowed);
        $router->with("r1");
        $router->getHandler("GET", $uri);

    }

    private function createRoute($methods, $path, $controller, array $middleware)
    {
        $route = new \SuperSimpleRouting\Route($methods, $path, $controller);
        $route->with($middleware);
        return $route;
    }

    private function createGroup($path, array $routes, array $middleware)
    {
        $group = new \SuperSimpleRouting\RouteGroup($path, $routes);
        $group->with($middleware);
        return $group;
    }

    private function createHandlerFactory($controller, $args, $middleware)
    {
        $handlerFactory = $this->createMock(SuperSimpleRouting\HandlerFactoryInterface::class);
        $handlerFactory
            ->method("make")
            ->willReturnCallback(function($c, $a, $m) use ($controller, $args, $middleware) {
                $handler = $this->createMock(Psr\Http\Server\RequestHandlerInterface::class);
                $this->assertEquals($controller, $c, "Controllers are equal");
                $this->assertEquals($args, $a, "Args are equal");
                $this->assertEquals($middleware, $m, "Middleware is equal");
                return $handler;
            });
        return $handlerFactory;
    }
}