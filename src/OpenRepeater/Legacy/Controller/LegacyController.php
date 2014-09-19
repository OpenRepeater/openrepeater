<?php

namespace OpenRepeater\Legacy\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LegacyController
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $doctrine_connection;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    public function __construct(Connection $doctrine_connection, SessionInterface $session)
    {
        $this->doctrine_connection = $doctrine_connection;
        $this->session             = $session;
    }

    /**
     * @param Request $request
     * @param string  $file
     *
     * @throws \Exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function legacyAction(Request $request, $file = 'index.php')
    {
        $legacy_file = realpath(__DIR__ .'/../../../../web/'. $file);

        if (!file_exists($legacy_file)) {
            throw new \Exception('File not found.', 404);
        }

        $this->registerGlobals();

        ob_start();
        require_once($legacy_file);
        $body = ob_get_contents();
        ob_end_clean();

        $response = new Response($body);
        $response->headers->add(['X-Framework'=> 'Silex']);

        return $response;
    }

    protected function registerGlobals()
    {
        $GLOBALS['app'] = [
            'db'      => $this->doctrine_connection,
            'session' => $this->session
        ];
    }
}
