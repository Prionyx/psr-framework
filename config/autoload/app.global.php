<?php

use App\Http\Middleware;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouteAdapter;
use Framework\Http\Router\Router;
use Framework\Template\Php\Extension\RouteExtension;
use Framework\Template\Php\PhpRenderer;
use Framework\Template\TemplateRender;
use Psr\Container\ContainerInterface;

return [
    'dependencies' => [
        'abstract_factories' => [
            Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
        'factories' => [
            Application::class => function (ContainerInterface $container) {
                return new Application(
                    $container->get(MiddlewareResolver::class),
                    $container->get(Router::class),
                    $container->get(Middleware\NotFoundHandler::class),
                    new Zend\Diactoros\Response()
                );
            },
            Router::class => function () {
                return new AuraRouteAdapter(new Aura\Router\RouterContainer());
            },
            MiddlewareResolver::class => function (ContainerInterface $container) {
                return new MiddlewareResolver($container);
            },
            Middleware\ErrorHandlerMiddleware::class => function (ContainerInterface $container) {
                return new Middleware\ErrorHandlerMiddleware(
                    $container->get('config')['debug'],
                    $container->get(TemplateRender::class)
                );
            },
            TemplateRender::class => function (ContainerInterface $container) {
                $renderer = new PhpRenderer('templates');
                $renderer->addExtension($container->get(RouteExtension::class));
                return $renderer;
            },
        ],
    ],
    'debug' => false,
];
