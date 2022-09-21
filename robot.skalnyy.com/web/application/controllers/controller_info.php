<?php

class Controller_Info extends Controller {

    public function __construct()
    {
        $this->view = new View();
    }

    function action_index()
    {
        $data = array('header' => 'Error 404', 'error' => '404 Сторінка не знайдена');
        $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
    }
}