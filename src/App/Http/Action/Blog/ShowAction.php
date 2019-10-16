<?php

namespace App\Http\Action\Blog;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ShowAction
{
    public function __invoke(ServerRequestInterface $reqest, callable $next)
    {
        $id = $reqest->getAttribute('id');

        if ($id > 5) {
            return $next($reqest);
        }

        return new JsonResponse(['id' => $id, 'title' => 'Post #' .$id]);
    }
}
