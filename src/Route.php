<?php

namespace SuperSimpleRouting;

/**
 * Class Route
 * @package SuperSimpleRouting
 */
class Route
{
    use MiddlewareAwareTrait;

    /**
     * @var array
     */
    private $methods = array();
    /**
     * @var string
     */
    private $path;
    /**
     * @var mixed
     */
    private $handler;


    /**
     * Route constructor.
     * @param $methods string|string[]
     * @param $path string
     * @param $handler mixed
     */
    public function __construct($methods, $path, $handler)
    {
        $this->path = $path;
        $this->methods = is_array($methods) ? $methods : [$methods];
        $this->handler = $handler;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
