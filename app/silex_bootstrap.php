<?php

use PimpleAwareEventDispatcher\PimpleAwareEventDispatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$env = getenv('SYMFONY_ENV') ?: 'dev';
$app['debug'] = in_array($env, ['dev', 'development', 'testing', 'test']);

$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__.'/config/parameters.yml'));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => $app['config']['parameters']['database_driver'],
        'host'     => $app['config']['parameters']['database_host'],
        'port'     => $app['config']['parameters']['database_port'],
        'dbname'   => $app['config']['parameters']['database_name'],
        'user'     => $app['config']['parameters']['database_user'],
        'password' => $app['config']['parameters']['database_password']
    ]
]);
$app['dispatcher'] = $app['pimple_aware_dispatcher'] = $app->share(
    $app->extend('dispatcher', function($dispatcher) use ($app) {
        return new PimpleAwareEventDispatcher($dispatcher, $app);
    }
));

$app->register(new \OpenRepeater\Legacy\Provider\LegacyProvider());
$app->register(new \OpenRepeater\Legacy\Provider\AuthenticationProvider());
/**
 * @see http://gonzalo123.com/2013/03/04/scaling-silex-applications-part-ii-using-routecollection/
 */
$app['routes'] = $app->extend('routes', function (RouteCollection $routes, Silex\Application $app) {
    $loader  = new YamlFileLoader(new FileLocator(__DIR__ . '/../src/OpenRepeater/Legacy/Resources/config'));

    $collection = $loader->load('routing.yml');
    $routes->addCollection($collection);

    return $routes;
});

$app->mount('/', new \OpenRepeater\Legacy\Provider\AuthenticationProvider());

return $app;