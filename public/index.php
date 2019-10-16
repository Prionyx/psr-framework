<?php

use App\Http\Action;
use App\Http\Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouteAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
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

$routes->get('cabinet', '/cabinet', function (ServerRequestInterface $request) use ($params) {
    $pipeline = new \Framework\Http\Pipeline\Pipeline();

    $pipeline->pipe(new Middleware\ProfilerMiddleware());
    $pipeline->pipe(new Middleware\BasicAuthMiddleware($params['users']));
    $pipeline->pipe(new Action\CabinetAction());

    return $pipeline($request, new Middleware\NotFoundHandler());
});

$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['id' => '\d+']);

$router = new AuraRouteAdapter($aura);
$resolver = new ActionResolver();

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
    $handler = new Middleware\NotFoundHandler();
    $response = $handler($reqest);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'Test');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
