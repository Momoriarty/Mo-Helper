<?php
define('BASE_URL', '');

function url($path)
{
    return BASE_URL . $path;
}

function init($path)
{
    require_once $path;
}

class Redirect
{
    private static $BASE_URL = BASE_URL;
    private static $data = [];

    public static function url($path)
    {
        return self::$BASE_URL . $path;
    }

    public static function to($path)
    {
        header("Location: " . self::$BASE_URL . $path);
        return new self();
    }

    public static function back()
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : self::$BASE_URL;
        header("Location: " . $referer);
        return new self();
    }

    public function with($key, $value)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'][$key] = $value;
        return new self();
    }
}

function session($key)
{
    if (isset($_SESSION['flash'][$key])) {
        $value = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $value;
    }

}
?>