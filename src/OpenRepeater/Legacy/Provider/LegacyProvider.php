<?php

namespace OpenRepeater\Legacy\Provider;

use OpenRepeater\Legacy\Controller\LegacyController;
use Silex\Application;
use Silex\ServiceProviderInterface;

class LegacyProvider implements  ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app['openrepeater.legacy.controller'] = $app->share(function() use ($app) {
            return new LegacyController(
                $app['db']
            );
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // no op
    }
}
 