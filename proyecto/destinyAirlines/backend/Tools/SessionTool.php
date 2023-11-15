<?php
class SessionTool
{
    public static function startSession(string $sessionName = 'MiSesion', int $sessionLifetime = 60 * 60 * 10)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_name($sessionName);
            session_set_cookie_params($sessionLifetime, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), true);
            session_start();
        } elseif (session_name() != $sessionName) {
            session_destroy();
            session_name($sessionName);
            session_start();
        }
    }

    public static function set(string|array $key, $value, string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
        if (is_array($key)) {
            $session =& $_SESSION;
            foreach ($key as $k) {
                if (!isset($session[$k]) || end($key) == $k) {
                    $session[$k] = [];
                }
                $session =& $session[$k];
            }
            $session = serialize($value);
        } else {
            $_SESSION[$key] = serialize($value);
        }
    }
    
    public static function get($key, string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
        if (is_array($key)) {
            $session =& $_SESSION;
            foreach ($key as $k) {
                if (!isset($session[$k])) {
                    return null;
                }
                $session =& $session[$k];
            }
            return is_array($session) ? $session : unserialize($session);
        } else {
            return isset($_SESSION[$key]) ? unserialize($_SESSION[$key]) : null;
        }
    }

    public static function getAll(string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
        $sessionArray = array_map('unserialize', $_SESSION);
        return $sessionArray;
    }

    public static function remove(string $key, string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
        unset($_SESSION[$key]);
    }

    public static function destroy(string $sessionName = 'MiSesion')
    {
        self::startSession($sessionName);
        $_SESSION = array();
        return session_destroy();
    }
}
