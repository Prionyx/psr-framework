<?php

use App\Http\Action;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$routes = new RouteCollection();
$resolver = new \Framework\Http\Router\ActionResolver();

$routes->get('home', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['id' => '\d+']);

$router = new Router($routes);

### Running

$reqest = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($reqest);
    foreach ($result->getAttributes() as $attribute => $value) {
        $reqest = $reqest->withAttribute($attribute, $value);
    }
    $handler = $result->getHeader();
    $action = $resolver->resove($handler);
    $response = $action($reqest);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'Test');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
