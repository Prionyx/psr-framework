<?php

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$routes = new RouteCollection();

$routes->get('home', '/', function (ServerRequestInterface $request) {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HtmlResponse('Hello, ' . $name . '!');
});

$routes->get('about', '/about', function () {
    return new HtmlResponse('I am a simple site');
});

$routes->get('blog', '/blog', function () {
    return new JsonResponse([
        ['id' => 2, 'title' => 'The Second Post'],
        ['id' => 1, 'title' => 'The First Post'],
    ]);
});

$routes->get('blog_show', '/blog/{id}', function (ServerRequestInterface $request) {
    $id = $request->getAttribute('id');
    if ($id > 5) {
        return new JsonResponse(['error' => 'Undefined page'], 404);
    }
    return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
}, ['id' => '\d+']);

$router = new Router($routes);

### Running

$reqest = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($reqest);
    foreach ($result->getAttributes() as $attribute => $value) {
        $reqest = $reqest->withAttribute($attribute, $value);
    }
    /** @var callable $action */
    $action = $result->getHeader();
    $response = $action($reqest);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'Test');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
