<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class ErrorHandlerMiddleware
 * @author yourname
 */
class ErrorHandlerMiddleware
{
    private $debug;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    public function __invoke(ServerRequestInterface $reqest, callable $next)
    {
        try {
            return $next($reqest);
        } catch (\Throwable $e) {
            //if ($this->debug) {
                //return new \Zend\Diactoros\Response\JsonResponse([
                    //'error' => 'Server error',
                    //'code' => $e->getCode(),
                    //'message' => $e->getMessage(),
                //], 500);
            //}
            //return new HtmlResponse('Server error', 500);
        }
    }
}
