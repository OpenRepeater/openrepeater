<?php

namespace OpenRepeater\Legacy\Controller;

use OpenRepeater\Legacy\Security\LoginManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthenticationController
{
    /**
     * @var \OpenRepeater\Legacy\Security\LoginManagerInterface
     */
    protected $login_manager;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $url_generator;

    /**
     * @param LoginManagerInterface $login_manager
     * @param UrlGeneratorInterface $url_generator
     */
    public function __construct(LoginManagerInterface $login_manager, UrlGeneratorInterface $url_generator)
    {
        $this->login_manager = $login_manager;
        $this->url_generator = $url_generator;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function loginAction(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $response = new RedirectResponse($this->url_generator->generate('homepage'));

        // Failed login
        if (!$this->login_manager->loginUser($response, $username, $password)) {
            $response->setTargetUrl($this->url_generator->generate('login', ['error'=> 'incorrectLogin']));
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logoutAction(Request $request)
    {
        $response = new RedirectResponse($this->url_generator->generate('login'));

        $this->login_manager->logoutUser($request, $response);

        return $response;
    }
}
