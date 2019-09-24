<?php

namespace Framework\Http;

class Request
{
    private $queryParams;
    private $parsedBody;

    public function __construct(array $queryParams = [], $parsedBody = null)
    {
        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query)
    {
        $this->queryParams = $query;
        return $this;
    }

    public function withParsedBody($data)
    {
        $this->queryParams = $data;
        return $this;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }
}
