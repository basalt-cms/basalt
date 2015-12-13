<?php

namespace Basalt\Auth;

use Basalt\Database\UserMapper;

class Authenticator
{
    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * Authenticator constructor.
     *
     * @param UserMapper $userMapper
     */
    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * Authenticates user with specified credentials.
     *
     * @param string $email User's email
     * @param string $password User's password
     * @return bool
     */
    public function authenticate($email, $password)
    {
        if ($user_id = $this->validate($email, $password)) {
            $_SESSION['user_id'] = $user_id;

            return true;
        }

        return false;
    }

    /**
     * Validates credentials and returns user's id when specified credentials are correct.
     *
     * @param string $email User's email
     * @param string $password User's password
     * @return int|bool
     */
    public function validate($email, $password)
    {
        $user = $this->userMapper->getByEmail($email);

        return (password_verify($password, $user->password)) ? $user->id : false;
    }

    /**
     * Is user logged in?€
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Returns User entity when user is logged in or false otherwise.
     *
     * @return \Basalt\Database\User|bool
     */
    public function getUser()
    {
        if ($this->isLoggedIn()) {
            return $this->userMapper->getById($_SESSION['user_id']);
        }

        return false;
    }

    /**
     * Deletes session variable responsible for authentication.
     */
    public function logOut()
    {
        unset($_SESSION['user_id']);
    }
}
