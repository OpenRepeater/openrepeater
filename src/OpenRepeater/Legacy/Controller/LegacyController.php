<?php

namespace OpenRepeater\Legacy\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LegacyController
{
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

        ob_start();
        require_once($legacy_file);
        $body = ob_get_contents();
        ob_end_clean();

        $response = new Response($body);
        $response->headers->add(['X-Framework'=> 'Silex']);

        return $response;
    }
}
