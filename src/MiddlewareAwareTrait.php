<?php

namespace SuperSimpleRouting;

/**
 * Trait MiddlewareAwareTrait
 * @package SuperSimpleRouting
 */
trait MiddlewareAwareTrait
{
    /**
     * @var array
     */
    private $middleware = array();

    /**
     * @param mixed $middleware
     */
    public function with($middleware)
    {
        if (!is_array($middleware)) {
            $this->middleware[] = $middleware;
        } else {
            foreach ($middleware as $mw) {
                $this->middleware[] = $mw;
            }
        }
    }

    /**
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}