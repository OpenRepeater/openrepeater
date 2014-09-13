<?php

namespace OpenRepeater\Legacy\Listener;

use OpenRepeater\Legacy\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var \OpenRepeater\Legacy\Security\LoginManagerInterface
     */
    protected $login_manager;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var UrlGeneratorInterface
     */
    protected $url_generator;

    /**
     * @param LoginManagerInterface $login_manager
     * @param SessionInterface      $session
     * @param UrlGeneratorInterface $url_generator
     */
    public function __construct(LoginManagerInterface $login_manager, SessionInterface $session, UrlGeneratorInterface $url_generator)
    {
        $this->login_manager = $login_manager;
        $this->session       = $session;
        $this->url_generator = $url_generator;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest']
            ],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $route = $event->getRequest()->get('_route');

        // ignore public routes
        if (in_array($route, ['auth_login', 'login', 'logout'])) {
            return;
        }

        if (!$this->session->get('userID') || !$this->session->get('username')) {
            $response = new RedirectResponse($this->url_generator->generate('login'));

            $event->setResponse($response);
        }
    }
}
