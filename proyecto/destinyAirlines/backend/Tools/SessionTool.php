<?php
class SessionTool
{
    public static function startSession(string $sessionName = 'MiSesion', int $sessionLifetime = 60 * 60 * 10)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_name($sessionName);
            session_set_cookie_params($sessionLifetime, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), true);
            session_start();
        }
        elseif(session_name() != $sessionName)
        {
            session_destroy();
            session_name($sessionName);
            session_start();
        }
    }

    public static function set(string $key, $value, string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
        $_SESSION[$key] = serialize($value);
    }

    public static function get(string $key, string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
        return isset($_SESSION[$key]) ? unserialize($_SESSION[$key]) : null;
    }

    public static function remove(string $key, string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
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
