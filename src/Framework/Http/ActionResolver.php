<?php

namespace Framework\Http;

class ActionResolver
{
    public function resove($handler): callable
    {
        return \is_string($handler) ? new $handler() : $handler;
    }
}
