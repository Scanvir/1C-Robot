<?php

class Controller_Admin extends Controller {

    public function __construct()
    {
        $this->model = new Model_Admin();
        $this->view = new View();
    }
	function testAuth(){
		if (empty($_COOKIE['auth'])) {
            if ($_COOKIE['auth'] == false) {
                $host = 'https://' . $_SERVER['HTTP_HOST'];
                header('HTTP/1.1 200');
                header('Location:' . $host . '/login');
            }
        }
		$this->data = array('user' => $this->model->getUser($_COOKIE['userId']));
	}
	function action_index(){
		$data = [];
		$week = $this->model->week();
		foreach($week as $key => $day){
			$data[$key] = ['name' => $day, 'lessons' => $this->model->getLessons($key)];
		}
		$this->testAuth();
		$this->view->generate('admin_view.php', $data);
	}
	function action_active($get){
		$day = $get['day'];
	    $num = $get['num'];

	    echo $this->model->updateActive($day, $num);
	}
}