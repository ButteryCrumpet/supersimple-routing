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
    private $controller;


    /**
     * Route constructor.
     * @param $methods string|string[]
     * @param $path string
     * @param $controller mixed
     */
    public function __construct($methods, $path, $controller)
    {
        $this->path = $path;
        $this->methods = is_array($methods) ? $methods : [$methods];
        $this->controller = $controller;
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
    public function getController()
    {
        return $this->controller;
    }
}
