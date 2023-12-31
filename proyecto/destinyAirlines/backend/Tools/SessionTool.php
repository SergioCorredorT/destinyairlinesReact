<?php
class SessionTool
{
    public static function startSession(string $sessionName = 'MiSesion', int $sessionLifetime = 60 * 60 * 10): bool
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
        return true;
    }

    public static function set(string|array $key, $value, string $sessionName = 'MiSesion'): bool
    {
        self::startSession($sessionName);
        $value = is_array($value) ? $value : serialize($value);
        if (is_array($key)) {
            $session = &$_SESSION;
            foreach ($key as $k) {
                if (!isset($session[$k])) {
                    $session[$k] = [];
                }
                $session = &$session[$k];
            }
            $session = $value;
        } else {
            $_SESSION[$key] = $value;
        }
        return true;
    }

    public static function get(string|array $key, string $sessionName = 'MiSesion'): null|array|string
    {
        self::startSession($sessionName);
        if (is_array($key)) {
            $session = &$_SESSION;
            foreach ($key as $k) {
                if (!isset($session[$k])) {
                    return null;
                }
                $session = &$session[$k];
            }
            return is_array($session) ? $session : unserialize($session);
        } else {
            return isset($_SESSION[$key]) ? (is_array($_SESSION[$key]) ? $_SESSION[$key] : unserialize($_SESSION[$key])) : null;
        }
    }

    public static function getAll(string $sessionName = 'MiSesion'): null|array
    {
        self::startSession($sessionName);
        $sessionArray = [];
        foreach ($_SESSION as $key => $value) {
            $sessionArray[$key] = is_array($value) ? $value : unserialize($value);
        }
        return $sessionArray;
    }

    public static function remove(string|array $key, string $sessionName = 'MiSesion'): bool
    {
        self::startSession($sessionName);
        if (is_array($key)) {
            $session = &$_SESSION;
            foreach ($key as $i => $k) {
                if (!isset($session[$k])) {
                    return false;
                }
                if ($i == count($key) - 2) {
                    unset($session[$k][$key[$i + 1]]);
                    return true;
                }
                $session = &$session[$k];
            }
        } else {
            unset($_SESSION[$key]);
        }
        return true;
    }

    public static function destroy(string $sessionName = 'MiSesion'): bool
    {
        self::startSession($sessionName);
        $_SESSION = array();
        return session_destroy();
    }
}
