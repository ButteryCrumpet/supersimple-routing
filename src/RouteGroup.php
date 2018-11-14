<?php

namespace SuperSimpleRouting;

/**
 * Class RouteGroup
 * @package SuperSimpleRoute
 */
class RouteGroup
{
    use MiddlewareAwareTrait;

    /**
     * @var string
     */
    private $path;

    /**
     * @var Route[]
     */
    private $routes = array();

    /**
     * RouteGroup constructor.
     * @param string $path
     * @param Route[] $routes
     */
    public function __construct(string $path, array $routes)
    {
        $this->path = $path;
        foreach($routes as $key => $route) {
            if (!($route instanceof Route || $route instanceof RouteGroup)) {
                throw new \InvalidArgumentException(sprintf(
                    "Route must be an instance of %s or %s. %s was given at index %s.",
                    Route::class,
                    RouteGroup::class,
                    gettype($route),
                    $key
                ));
            }
            $this->routes[] = $route;
        }
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
