<?php

namespace Framework\Container;

class Container
{
    private $definitions = [];
    private $results = [];

    public function get($id)
    {
        if (array_key_exists($id, $this->results)) {
            return $this->results[$id];
        }

        if (!array_key_exists($id, $this->definitions)) {
            throw new ServiceNotFoundException('Uncnown sevice "' . $id . '"');
        }

        $definitions = $this->definitions[$id];

        if ($definitions instanceof \Closure) {
            $this->results[$id] = $definitions($this);
        } else {
            $this->results[$id] = $definitions;
        }

        return $this->results[$id];
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->definitions);
    }

    public function set($id, $value): void
    {
        if (array_key_exists($id, $this->results)) {
            unset($this->results[$id]);
        }

        $this->definitions[$id] = $value;
    }
}
