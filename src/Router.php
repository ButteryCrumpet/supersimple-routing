<?php

namespace SuperSimpleRouting;

use FastRoute;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router
{
    use MiddlewareAwareTrait;

    /**
     * @var HandlerFactoryInterface
     */
    private $handlerFactory;
    /**
     * @var Route[]
     */
    private $routes;
    /**
     * @var Route
     */
    private $notFound;
    /**
     * @var Route
     */
    private $notAllowed;

    /**
     * Router constructor.
     * @param Route[]|RouteGroup[] $routes
     * @param HandlerFactoryInterface $handlerFactory
     * @param Route $notFound
     * @param Route $notAllowed
     */
    public function __construct(
        array $routes,
        HandlerFactoryInterface $handlerFactory,
        Route $notFound,
        Route $notAllowed
    ) {
        $this->routes = $routes;
        $this->handlerFactory = $handlerFactory;
        $this->notFound = $notFound;
        $this->notAllowed = $notAllowed;
    }


    /**
     * @param string $method
     * @param UriInterface $uri
     * @return RequestHandlerInterface
     */
    public function getHandler(string $method, UriInterface $uri): RequestHandlerInterface
    {
        $path = $uri->getPath();
        $path = empty($path) ? "/" : $path;

        $dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
            $this->setUp($r);
        });
        $routeInfo = $dispatcher->dispatch($method, $path);
        switch ($routeInfo[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
                return $this->handlerFactory->make(
                    $this->notAllowed->getController(),
                    $routeInfo[2] ?? [],
                    $this->notAllowed->getMiddleware()
                );
            case Dispatcher::FOUND:
                $route = $routeInfo[1];
                return $this->handlerFactory->make(
                    $route->getController(),
                    $routeInfo[2] ?? [],
                    $route->getMiddleware()
                );
            default:
                return $this->handlerFactory->make(
                    $this->notFound->getController(),
                    $routeInfo[2] ?? [],
                    $this->notFound->getMiddleware()
                );
        }
    }

    private function setUp(RouteCollector $r)
    {
        foreach ($this->routes as $route) {
            $this->addRouteOrGroup($route, $r);
        }
    }

    private function addRouteOrGroup($route, $r, $group = null)
    {
        if ($route instanceof Route) {
            $this->addRoute($route, $r, $group);
        } elseif ($route instanceof RouteGroup) {
            $this->addGroup($route, $r, $group);
        } else {

            $type = gettype($route);
            if ($type === "object") {
                $type = get_class($route);
            }

            throw new \InvalidArgumentException(sprintf(
                "Routes must be either %s or %s. %s was given.",
                Route::class,
                RouteGroup::class,
                $type
            ));
        }
    }

    private function addRoute(Route $route, RouteCollector $collector, RouteGroup $group = null)
    {
        if (!is_null($group)) {
            $route->with($group->getMiddleware());
        }
        $route->with($this->getMiddleware());
        $collector->addRoute(
            $route->getMethods(),
            $route->getPath(),
            $route
        );
    }

    private function addGroup(RouteGroup $group, RouteCollector $collector, RouteGroup $parentGroup = null)
    {
        $collector->addGroup($group->getPath(), function(FastRoute\RouteCollector $r) use ($group, $parentGroup) {
            foreach ($group->getRoutes() as $route) {
                if (!is_null($parentGroup)) {
                    $group->with($parentGroup->getMiddleware());
                }
                $this->addRouteOrGroup($route, $r, $group);
            }
        });
    }
}
