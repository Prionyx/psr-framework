<?php

namespace Framework\Http\Router\Exception;

/**
 * Class RouteNotFoundException
 * @author yourname
 */
class RouteNotFoundException extends \LogicException
{
    private $name;
    private $params;

    public function __construct($name, array $params)
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
