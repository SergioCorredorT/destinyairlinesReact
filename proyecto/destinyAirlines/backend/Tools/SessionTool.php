<?php
class SessionTool
{
    public static function startSession($sessionName = 'MiSesion', $sessionLifetime = 60 * 60 * 10)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_name($sessionName);
            session_set_cookie_params($sessionLifetime, "/", $_SERVER["HTTP_HOST"], isset($_SERVER["HTTPS"]), true);
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::startSession();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::startSession();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function remove($key)
    {
        self::startSession();
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = array();
            return session_destroy();
        }
    }
}
