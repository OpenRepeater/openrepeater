<?php

namespace OpenRepeater\Legacy\Security;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LoginManager implements LoginManagerInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    protected $password_encoder;

    /**
     * @param Connection               $db
     * @param SessionInterface         $session
     * @param PasswordEncoderInterface $password_encoder
     */
    public function __construct(Connection $db, SessionInterface $session, PasswordEncoderInterface $password_encoder)
    {
        $this->db               = $db;
        $this->session          = $session;
        $this->password_encoder = $password_encoder;
    }

    /**
     * @param Response $response
     * @param          $username
     * @param          $password
     *
     * @return Response
     */
    public function loginUser(Response $response, $username, $password)
    {
        if ($this->validateCredentials($username, $password)) {
            $result = $this->db->executeQuery('SELECT UserID FROM users where username = ?', [$username])->fetch();
            $this->session->set('username', $username);
            $this->session->set('userID', $result['UserID']);
        }

        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function logoutUser(Request $request, Response $response)
    {
        $this->session->remove('username');
        $this->session->remove('userID');

        return $response;
    }

    /**
     * @param $username
     * @param $password
     *
     * @return boolean
     */
    public function validateCredentials($username, $password)
    {
        $sql = 'SELECT UserID, password, salt FROM users WHERE username = ?';
        $result = $this->db->executeQuery($sql, [$username])->fetch();

        if ($result) {
            if ($this->password_encoder->isPasswordValid($result['password'], $password, $result['salt'])) {
                return true;
            }
        }

        return false;
    }
}
