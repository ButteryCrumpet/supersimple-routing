<?php

namespace SuperSimpleRouting;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface HandlerFactoryInterface
 * @package SuperSimpleRouting
 */
interface HandlerFactoryInterface
{
    /**
     * @param $controller
     * @param array $args
     * @param array $middleware
     * @return RequestHandlerInterface
     */
    public function make($controller, array $args, array $middleware): RequestHandlerInterface;
}