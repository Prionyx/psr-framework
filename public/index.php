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

/**
 * @var \Framework\Container\Container $container
 * @var \Framework\Http\Application $app
 */

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

ini_set('display_errors', 'on');

$container = require 'config/container.php';
$app = $container->get(Application::class);

require 'config/pipeline.php';
require 'config/routes.php';

$reqest = ServerRequestFactory::fromGlobals();
$response = $app->run($reqest, new Response());

### Sending

$emitter = new SapiEmitter;
$emitter->emit($response);
