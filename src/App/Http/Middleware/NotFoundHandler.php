<?php

namespace App\Http\Middleware;

use Framework\Template\TemplateRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundHandler
{
    private $template;

    public function __construct(TemplateRender $template)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse($this->template->render('error/404', [
            'reqest' => $request,
        ]), 404);
    }
}
