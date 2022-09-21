<?php

class Controller {

    public $model;
    public $view;
    public $cart;
    public $user;

    function __construct()
    {
        $this->view = new View();
    }

    function action_index()
    {
    }
}