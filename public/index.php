<?php

use App\Http\Action;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouteAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
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
$routes->get('cabinet', '/cabinet', new Action\CabinetAction($params['users']));
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
    $response = new HtmlResponse('Undefined page', 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'Test');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
