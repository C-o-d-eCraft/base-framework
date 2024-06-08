<?php

use Craft\Components\DIContainer\DIContainer;
use Craft\Components\Logger\DebugTagGenerator;
use Craft\Contracts\DataBaseConnectionInterface;
use Craft\Contracts\EventDispatcherInterface;
use Craft\Contracts\HttpKernelInterface;
use Craft\Contracts\LoggerInterface;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

const PROJECT_ROOT = __DIR__ . '/../';

const PROJECT_SOURCE_ROOT = PROJECT_ROOT . 'src/';

const PROJECT_WEB_ROOT = PROJECT_ROOT . 'web/';

require_once PROJECT_ROOT . 'vendor/autoload.php';

$debugTagGenerator = new DebugTagGenerator();
$debugTagGenerator->init();

$container = DIContainer::createContainer(require_once PROJECT_ROOT . 'config/di-container.php');

require_once PROJECT_ROOT . 'routes/web.php';

$container->singleton(LoggerInterface::class);
$container->singleton(EventDispatcherInterface::class);
$container->singleton(DataBaseConnectionInterface::class);

if (getenv('RENDER_MODE') === 'SPA') {
    include_once PROJECT_WEB_ROOT . 'index.html';
    exit;
}

$kernel = $container->make(HttpKernelInterface::class);

$response = $container->call($kernel, 'handle');

$response->send();
