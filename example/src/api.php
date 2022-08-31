<?php

use DI\Container;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . "/../vendor/autoload.php";

$cb = new ContainerBuilder();
$cb->addDefinitions(include __DIR__ . "/config/settings.php");
$container = $cb->build();

$app = AppFactory::createFromContainer($container);

include __DIR__ . "/config/dependencies.php";
include __DIR__ . "/config/middleware.php";
include __DIR__ . "/config/routes.php";

$app->run();
