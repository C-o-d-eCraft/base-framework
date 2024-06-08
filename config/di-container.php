<?php

use Craft\Components\DatabaseConnection\MySqlConnectionFactory;
use Craft\Components\DIContainer\DIContainer;
use Craft\Components\ErrorHandler\CliErrorHandler;
use Craft\Components\ErrorHandler\HttpErrorHandler;
use Craft\Components\Logger\StdoutLogger;
use Craft\Components\QueryBuilder\QueryBuilderMysql;
use Craft\Console\ConsoleKernel;
use Craft\Console\Input;
use Craft\Console\Output;
use Craft\Contracts\CliErrorHandlerInterface;
use Craft\Contracts\ConsoleKernelInterface;
use Craft\Contracts\DataBaseConnectionInterface;
use Craft\Contracts\ErrorHandlerInterface;
use Craft\Contracts\EventDispatcherInterface;
use Craft\Contracts\HttpKernelInterface;
use Craft\Contracts\InputInterface;
use Craft\Contracts\LoggerInterface;
use Craft\Contracts\MiddlewareInterface;
use Craft\Contracts\OutputInterface;
use Craft\Contracts\QueryBuilderInterface;
use Craft\Contracts\RequestInterface;
use Craft\Contracts\ResponseInterface;
use Craft\Contracts\RouterInterface;
use Craft\Contracts\RoutesCollectionInterface;
use Craft\Contracts\StreamInterface;
use Craft\Contracts\ViewInterface;
use Craft\Http\Factory\RequestFactory;
use Craft\Http\HttpKernel;
use Craft\Http\Message\Response;
use Craft\Http\Message\Stream;
use Craft\Http\Route\Router;
use Craft\Http\Route\RoutesCollection;
use Craft\Http\View\View;

return [
    EventDispatcherInterface::class => function () {
        return new EventDispatcher(require_once('events.php'));
    },

    RoutesCollectionInterface::class => new RoutesCollection(),

    RouterInterface::class => function (DIContainer $container) {
        return new Router(
            $container,
            $container->make(RoutesCollectionInterface::class),
            $container->make(MiddlewareInterface::class),
            $container->make(RequestInterface::class)
        );
    },

    RequestInterface::class => function () {
        return RequestFactory::createRequest();
    },

    ResponseInterface::class => new Response(),

    StreamInterface::class => new Stream(fopen('php://stdin', 'r')),

    LoggerInterface::class => new StdoutLogger(),

    ErrorHandlerInterface::class => HttpErrorHandler::class,

    HttpKernelInterface::class => function (DIContainer $container) {
        return new HttpKernel(
            $container->make(RequestInterface::class),
            $container->make(ResponseInterface::class),
            $container->make(RouterInterface::class),
            $container->make(LoggerInterface::class),
            $container->make(ErrorHandlerInterface::class),
            $container
        );
    },

    InputInterface::class => function () {
        return new Input($_SERVER['argv']);
    },

    OutputInterface::class => new Output(),

    CliErrorHandlerInterface::class => CliErrorHandler::class,

    ConsoleKernelInterface::class => function (DIContainer $container) {
        return new ConsoleKernel(
            $container,
            $container->make(InputInterface::class),
            $container->make(OutputInterface::class),
            $container->make(EventDispatcherInterface::class),
            $container->make(LoggerInterface::class),
            $container->make(CliErrorHandlerInterface::class),
            require_once('plugins.php')
        );
    },

    DataBaseConnectionInterface::class => function () {
        return (new MySqlConnectionFactory)->createConnection(require('db-config.php'));
    },

    QueryBuilderInterface::class => function (DIContainer $container) {
        return new QueryBuilderMysql(
            $container->make(DataBaseConnectionInterface::class),
        );
    },

    ViewInterface::class => new View(),
];
