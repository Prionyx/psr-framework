<?php

namespace Framework\Container;

interface ContinerInterface
{
    /**
     * @param $id
     * @return mixed
     * @throws ServiceNotFoundException
     */
    public function get($id);

    public function has($id);
}
