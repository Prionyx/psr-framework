<?php

use App\Http\Action;
use App\Http\Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouteAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$params = [
    'users' => ['admin' => 'password'],
];

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);

$routes->get('cabinet', '/cabinet', [
    new Middleware\BasicAuthMiddleware($params['users']),
    Action\CabinetAction::class,
]);

$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['id' => '\d+']);

$router = new AuraRouteAdapter($aura);
$resolver = new MiddlewareResolver();
$pipeline = new Pipeline();

$pipeline->pipe($resolver->resove(Middleware\ProfilerMiddleware::class));

### Running

$reqest = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($reqest);
    foreach ($result->getAttributes() as $attribute => $value) {
        $reqest = $reqest->withAttribute($attribute, $value);
    }
    $handler = $result->getHeader();
    if (is_array($handler)) {
        $middleware = new Pipeline();
        foreach ($handler as $item) {
            $middleware->pipe($resolver->resove($item));
        }
    } else {
        $middleware = $resolver->resove($handler);
    }
    $pipeline->pipe($middleware);
} catch (RequestNotMatchedException $e) {}

$response = $pipeline($reqest, new Middleware\NotFoundHandler());

### Postprocessing

$response = $response->withHeader('X-Developer', 'Test');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
