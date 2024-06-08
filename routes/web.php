<?php

use Craft\Contracts\ContainerInterface;
use Craft\Contracts\RoutesCollectionInterface;
use Craft\Http\Route\RoutesCollection;

/** @var ContainerInterface $container */
$container->singleton(RoutesCollectionInterface::class);

$collection = $container->make(RoutesCollectionInterface::class);

$collection->addGlobalMiddleware(
    []
);