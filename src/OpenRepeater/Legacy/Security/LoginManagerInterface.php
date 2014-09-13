<?php

namespace OpenRepeater\Legacy\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface LoginManagerInterface
{
    /**
     * @param Response $response
     * @param          $username
     * @param          $password
     *
     * @return Response
     */
    public function loginUser(Response $response, $username, $password);

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function logoutUser(Request $request, Response $response);

    /**
     * @param $username
     * @param $password
     *
     * @return boolean
     */
    public function validateCredentials($username, $password);
}
