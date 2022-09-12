<?php

class Controller_Info extends Controller {

    public function __construct()
    {
        $this->view = new View();
    }

    function action_index()
    {
        $data = array('error' => '404 Сторінка не знайдена');
        $this->view->generate('common/info_view.php', $data);
    }
}