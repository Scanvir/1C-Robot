<?php

class Controller_Home extends Controller {

	public function __construct() {
        $this->view = new View();
    }
    function action_index() {
        if(isset($_COOKIE['userId'])){
            $userId = (int)$_COOKIE['userId'];
            User::activeUser($userId);
        }
        $data = ['header' => 'Головна', 'view' => 'index', 'branch' => Branch::GetWorkBranch()];
        $this->view->generate('common/home_view.php', 'common/template_view.php', $data);
    }
}