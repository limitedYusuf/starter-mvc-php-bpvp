<?php

class HomeController extends Controller
{
  public function index()
  {
    $data = [
      'title' => 'Beranda : JWP BPVP',
    ];

    $this->view('home', $data);
  }
}