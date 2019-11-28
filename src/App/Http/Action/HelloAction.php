<?php

namespace App\Http\Action;

use Framework\Template\TemplateRender;
use Zend\Diactoros\Response\HtmlResponse;

class HelloAction
{
    private $template;

    public function __construct(TemplateRender $template)
    {
        $this->template = $template;
    }

    public function __invoke()
    {
        return new HtmlResponse($this->template->render('app/hello'));
    }
}
