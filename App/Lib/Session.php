<?php
namespace Lib;

session_start();

class Session
{
    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
    }

    public function getFlash()
    {
        $flash = "Username = defaultNickName, Password = pwd";
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
        }

        return $flash;
    }

    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function setAuthenticated($authenticated = true, $user)
    {
        if (!is_bool($authenticated))
        {
            throw new Error('You need provide a boolean value');
        }

        $_SESSION['auth'] = $authenticated;
        $_SESSION['user'] = $user->getNickName();
        $_SESSION['level'] = $user->getAccessLevel();
        return $user;
    }

    public function setFlash($value)
    {
        $_SESSION['flash'] = $value;
    }

    public function kick($reason) {
        session_unset();
        error_log('kicked => reason: ' . $reason);
        unset($_GET);
        unset($_POST);
        header('Location: /');
    }
}
