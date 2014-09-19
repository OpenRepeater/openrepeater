<?php

namespace OpenRepeater\Legacy\Provider;

use OpenRepeater\Legacy\Controller\AuthenticationController;
use OpenRepeater\Legacy\Listener\AuthenticationListener;
use OpenRepeater\Legacy\Security\Core\PasswordEncoder;
use OpenRepeater\Legacy\Security\LoginManager;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class AuthenticationProvider implements ControllerProviderInterface, ServiceProviderInterface
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
        $app['openrepeater.legacy.authentication.controller'] = $app->share(function() use ($app) {
            return new AuthenticationController(
                $app['openrepeater.legacy.authentication.login_manager'],
                $app['url_generator']
            );
        });

        $app['openrepeater.legacy.authentication.listener'] = $app->share(function() use ($app) {
            return new AuthenticationListener(
                $app['openrepeater.legacy.authentication.login_manager'],
                $app['session'],
                $app['url_generator']
            );
        });

        $app['openrepeater.legacy.authentication.login_manager'] = $app->share(function() use ($app) {
            return new LoginManager(
                $app['db'],
                $app['session'],
                $app['openrepeater.legacy.password_encoder']
            );
        });

        $app['openrepeater.legacy.password_encoder'] = $app->share(function() use ($app) {
            return new PasswordEncoder();
        });
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $app['pimple_aware_dispatcher']->addSubscriberService('openrepeater.legacy.authentication.listener', '\OpenRepeater\Legacy\Listener\AuthenticationListener');

        return $controllers;
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
