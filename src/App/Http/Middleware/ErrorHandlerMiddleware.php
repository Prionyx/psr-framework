<?php

namespace App\Http\Middleware;

use Framework\Template\TemplateRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandlerMiddleware
{
    private $debug;
    private $template;

    public function __construct(bool $debug, TemplateRender $template)
    {
        $this->debug = $debug;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $reqest, callable $next)
    {
        try {
            return $next($reqest);
        } catch (\Throwable $e) {
            $view = $this->debug ? 'error/error-debug' : 'error/error';
            return new HtmlResponse($this->template->render($view, [
                'request' => $reqest,
                'exception' => $e,
            ]), $e->getCode() ?: 500);
        }
    }
}
