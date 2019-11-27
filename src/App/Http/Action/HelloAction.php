<?php

namespace App\Http\Action;

use Framework\Template\TemplateRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HelloAction
{
    private $template;

    public function __construct(TemplateRender $template)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse($this->template->render('hello', [
            'name' => $name,
        ]));
    }
}
