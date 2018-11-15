<?php

namespace SuperSimpleRouting;

/**
 * Trait RouteBuilderTrait
 * @package SuperSimpleRouting
 */
trait RouteBuilderTrait
{
    /**
     * @var Route[]|RouteGroup[]
     */
    private $routes = array();

    /**
     * @param string[] $methods
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function route(array $methods, $path, $handler)
    {
        $route = new Route($methods, $path, $handler);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * @param string $path
     * @param \Closure $callable
     * @return RouteGroup
     */
    public function group($path, \Closure $callable) {
        $group = new RouteGroup($path);
        $callable($group);
        $this->routes[] = $group;
        return $group;
    }

    /**
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function get($path, $handler)
    {
        return $this->route(["GET"], $path, $handler);
    }

    /**
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function post($path, $handler)
    {
        return $this->route(["POST"], $path, $handler);
    }

    /**
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function put($path, $handler)
    {
        return $this->route(["PUT"], $path, $handler);
    }

    /**
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function delete($path, $handler)
    {
        return $this->route(["DELETE"], $path, $handler);
    }

    /**
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function patch($path, $handler)
    {
        return $this->route(["PATCH"], $path, $handler);
    }

    /**
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function any($path, $handler)
    {
        return $this->route(
            ["GET", "HEAD", "POST", "PUT", "DELETE", "CONNECT", "OPTIONS", "TRACE", "PATCH"],
            $path,
            $handler
        );
    }

    /**
     * @return Route[]|RouteGroup[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}