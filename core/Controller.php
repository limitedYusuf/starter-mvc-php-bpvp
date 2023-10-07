<?php
class Controller {
    // Made By : Yusuf Limited
    
    public function view($view, $data = [])
    {
        if(isset($data)) extract($data);
        require_once 'app/Views/' . $view . '.php';
    }

    public function model($model)
    {
        require_once 'app/Models/' . $model . '.php';
        return new $model;
    }

    public function helper($helper)
    {
        require_once 'app/Helpers/' . $helper . '.php';
        return new $helper();
    }

    public function function($function)
    {
        require_once 'app/Functions/' . $function . '.php';
    }
}