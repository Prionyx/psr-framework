<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ServerRequestInterface;

class MiddlewareResolver
{
    public function resove($handler): callable
    {
        if (\is_string($handler)) {
            return function (ServerRequestInterface $request, callable $next) use ($handler) {
                $object = new $handler();
                return $object($request, $next);
            };
        }

        return $handler;
    }
}
