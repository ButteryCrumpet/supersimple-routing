<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleRouting\Router;

class RouterTest extends TestCase
{
    private $notFoundAllowed;

    public function setUp()
    {
        $this->notFoundAllowed = $this->createMock(\SuperSimpleRouting\Route::class);
        $this->notFoundAllowed->method("getMiddleware")->willReturn([]);
    }

    public function testItInitializes()
    {
        $handlerFact = $this->createMock(SuperSimpleRouting\HandlerFactoryInterface::class);
        $handlerFact
            ->method("make")
            ->willReturn($this->createMock(Psr\Http\Server\RequestHandlerInterface::class));

        $this->assertInstanceOf(
            Router::class,
            new Router($handlerFact, $this->notFoundAllowed, $this->notFoundAllowed)
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

        $router = new Router($handlerFact, $this->notFoundAllowed, $this->notFoundAllowed);
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
        $router = new Router($handlerFact, $this->notFoundAllowed, $this->notFoundAllowed);
        $router->get("/", 'Cont1');
        $router->group("/posts", function($group) {
            $group->post("/create", "Cont2")->with(["1", "2"]);
            $group->group("/{id}", function($group) {
               $group->get("/top", "Cont3")->with("1");
            });
        })->with(["m1"]);
        $router->with("r1");
        $router->getHandler("GET", $uri);
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