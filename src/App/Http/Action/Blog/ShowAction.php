<?php

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Framework\Template\TemplateRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ShowAction
{
    private $posts;
    private $template;

    public function __construct(PostReadRepository $posts, TemplateRender $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $post = $this->posts->find($request->getAttribute('id'));
        if (!$post) {
            return $next($request);
        }

        return new HtmlResponse($this->template->render('app/blog/show', [
            'post' => $post,
        ]));
    }
}
