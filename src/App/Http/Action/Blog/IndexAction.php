<?php

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Framework\Template\TemplateRender;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class IndexAction
{
    private $posts;
    private $template;

    public function __construct(PostReadRepository $posts, TemplateRender $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    public function __invoke()
    {
        $posts = $this->posts->getAll();

        return new HtmlResponse($this->template->render('app/blog/index', [
            'posts' => $posts,
        ]));
    }
}
