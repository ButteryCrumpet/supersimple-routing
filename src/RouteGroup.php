<?php

namespace SuperSimpleRouting;

/**
 * Class RouteGroup
 * @package SuperSimpleRoute
 */
class RouteGroup
{
    use MiddlewareAwareTrait;
    use RouteBuilderTrait;

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
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
