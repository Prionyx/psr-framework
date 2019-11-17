<?php

use App\Http\Action;
use App\Http\Middleware;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouteAdapter;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

ini_set('display_errors', 'on');

### Configuration

$container = new Container();

$container->set('config', [
    'debug' => true,
    'users' => ['admin' => 'password'],
]);

$container->set(Application::class, function (Container $container) {
    return new Application(
        $container->get(MiddlewareResolver::class),
        $container->get(Router::class),
        new Middleware\NotFoundHandler(),
        new Response()
    );
});

$container->set(Middleware\BasicAuthMiddleware::class, function (Container $container) {
    return new Middleware\BasicAuthMiddleware($container->get('config')['users']);
});

$container->set(Middleware\ErrorHandlerMiddleware::class, function (Container $container) {
    return new Middleware\ErrorHandlerMiddleware($container->get('config')['debug']);
});

$container->set(DispatchMiddleware::class, function (Container $container) {
    return new DispatchMiddleware($container->get(MiddlewareResolver::class));
});

$container->set(MiddlewareResolver::class, function (Container $container) {
    return new MiddlewareResolver();
});

$container->set(RouteMiddleware::class, function (Container $container) {
    return new RouteMiddleware($container->get(Router::class));
});

$container->set(Router::class, function (Container $container) {
    return new AuraRouteAdapter(new Aura\Router\RouterContainer());
});

$app = $container->get(Application::class);

$app->pipe($container->get(Middleware\ErrorHandlerMiddleware::class));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe($container->get(Framework\Http\Middleware\RouteMiddleware::class));
$app->pipe($container->get(Framework\Http\Middleware\DispatchMiddleware::class));


$app->get('home', '/', Action\HelloAction::class);
$app->get('about', '/about', Action\AboutAction::class);

$app->get('cabinet', '/cabinet', [
$container->get(Middleware\BasicAuthMiddleware::class),
    Action\CabinetAction::class,
]);

$app->get('blog', '/blog', Action\Blog\IndexAction::class);
$app->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['tokens' => ['id' => '\d+']]);

### Running

$reqest = ServerRequestFactory::fromGlobals();
$response = $app->run($reqest, new Response());

### Sending

$emitter = new SapiEmitter;
$emitter->emit($response);
