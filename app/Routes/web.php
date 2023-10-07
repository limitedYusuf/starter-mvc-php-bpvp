<?php

$router->get('', 'HomeController@index');
$router->get('console', 'AuthController@index');
$router->post('login_process', 'AuthController@login');