<?php

namespace Tests\Framework\Http;

use Framework\Container\ContinerInterface;
use Framework\Container\ServiceNotFoundException;

class DummyContainer implements ContinerInterface
{
    public function get($id)
    {
        if (!class_exists($id)) {
            throw new ServiceNotFoundException($id);
        }
        return new $id();
    }

    public function has($id): bool
    {
        return class_exists($id);
    }
}
