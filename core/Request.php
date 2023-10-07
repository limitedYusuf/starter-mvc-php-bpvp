<?php

class Request
{
  // Made By : Yusuf Limited
  public function post($post)
  {
    if (isset($_POST[$post])) {
      return $this->sanitizeInput($_POST[$post]);
    } else {
      die("value {$post} tidak diketahui");
    }
  }

  public function get($get)
  {
    if (isset($_GET[$get])) {
      return $this->sanitizeInput($_GET[$get]);
    } else {
      die("value {$get} tidak diketahui");
    }
  }

  public function postAll()
  {
    return isset($_POST) ? $_POST : null;
  }

  public function files()
  {
    return isset($_FILES) ? $_FILES : null;
  }

  private function sanitizeInput($input)
  {
    return stripslashes(strip_tags(htmlentities($input, ENT_QUOTES)));
  }
}
