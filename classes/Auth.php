<?php

class Auth
{

    public static function isLoggedIn()
    { //not going to call this method on an instancie of this class that is why it is static

        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
    }

    public static function requireLogin()
    {
        if (!static::isLoggedIn()) {
            die("unauthorized.");
        }
    }

    public static function login()
    {
        session_regenerate_id(true);
        $_SESSION['is_logged_in'] = true;
    }

    public static function logout()
    {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        session_destroy();
    }

}
