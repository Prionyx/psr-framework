<?php

namespace Framework\Container;

class Container
{
    private $definitions = [];

    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ServiceNotFoundException('Uncnown sevice "' . $id . '"');
        }

        $definitions = $this->definitions[$id];

        if ($definitions instanceof \Closure) {
            $result = $definitions();
        } else {
            $result = $definitions;
        }

        return $result;
    }

    public function set($id, $value): void
    {
        $this->definitions[$id] = $value;
    }
}
