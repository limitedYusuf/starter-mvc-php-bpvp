<?php

class Helper
{
  // Made By : Yusuf Limited

  public static function base_url($url = null)
  {
    if (isset($url)) {
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = rtrim($url, '/');

      return BASE_URL . strtolower($url);
    }

    return BASE_URL;
  }

  public static function redirect($url = null)
  {
    if (isset($url)) {
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = rtrim($url, '/');

      header('Location: ' . BASE_URL . $url);
    } else {
      header('Location: ' . BASE_URL);
    }
  }

  public static function setSession($key, $value)
  {
    if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
      $_SESSION[$key] = [];
    }

    $_SESSION[$key] = $value;
  }

  public static function cekSession($key)
  {
    if (isset($_SESSION[$key])) {
      return true;
    } else {
      return false;
    }
  }

  public static function getSession($key, $param)
  {
    if (isset($_SESSION[$key][$param])) {
      return $_SESSION[$key][$param];
    }

    return null;
  }
}
