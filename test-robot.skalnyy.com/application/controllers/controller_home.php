<?php

class Controller_Home extends Controller {

	private $data;
	
    public function __construct()
    {
        $this->model = new Model_Home();
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
    function action_index()
    {
        $data = [
            'Укр' => [
                'name' => 'Укр мова', 
                'link' => 'https://us04web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
            'Мат' => [
                'name' => 'Математика', 
                'link' => 'https://us04web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
            'Англ' => [
                'name' => 'Англ мова', 
                'link' => 'https://us04web.zoom.us/j/79607648259?pwd=7ecyqfkdO9erUsGbLIM80sPUabIHtU.1'],
            'Мист' => [
                'name' => 'Мистецтво',
                'link' => 'https://us02web.zoom.us/j/5959708901?pwd=Qi9sd2xtTE13Z2F5MHVhSUt2VTNOZz09'],
            'ЯДС' => [
                'name' => 'Я досліджую світ', 
                'link' => 'https://us02web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
            'Пет' => [
                'name' => 'Петерсон',
                'link' => 'https://us02web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
            'Трен' => [
                'name' => 'Тренувальні вправи',
                'link' => 'https://us05web.zoom.us/j/87173838790?pwd=am5rSkdHNkpQZmpMQ1pPTy9ZYnBsUT09'],
            'УЧит' => [
                'name' => 'Укр читання',
                'link' => 'https://us04web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
            'Физ' => [
                'name' => 'Фізична культура',
                'link' => 'https://us02web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
			'Лог' => [
                'name' => 'Логіка',
                'link' => 'https://us02web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
			'УкрЧ' => [
                'name' => 'Українське читання', 
                'link' => 'https://us04web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09'],
			'Муз' => [
                'name' => 'Музична культура',
                'link' => 'https://us02web.zoom.us/j/85991083541?pwd=WUlPQnp1ZWhpRkdjMjQrVWFQcy9jQT09']];
        
		$this->testAuth();
        $this->view->generate('home_view.php', $data);
    }
    function action_group1() {
        $data[] = 'Ананьєва Дарья';
        $data[] = 'Войцехович Емілія';
        $data[] = 'Воронюк Антоніна';
        $data[] = 'Головченко Олександра';
        $data[] = 'Крохов Данііл';
        $data[] = 'Карманова Поліна';
        $data[] = 'Нестеренко Соломія';
        $data[] = 'Солоневич Софія';
        $data[] = 'Серьогіна Маргарита';
        $data[] = 'Скальний Роман';
        $data[] = 'Хараман Іван';
        $data[] = 'Шовкун Вероніка';
        $data[] = 'Щєдрін Артем';
        
        $data = ['num' => 1, 'list' => $data];

        $this->testAuth();
        $this->view->generate('group_view.php', $data);
    }
    function action_group2() {
        $data[] = 'Алінагі Ерік';
        $data[] = 'Волков Ярослав';
        $data[] = 'Ганжа Адемі';
        $data[] = 'Грачова Уляна';
        $data[] = 'Дерюгіна Єлизавета';
        $data[] = 'Ісаєнко Максим';
        $data[] = 'Кравець Катерина';
        $data[] = 'Норіцин Тимур';
        $data[] = 'Подскребалін Даніель';
        $data[] = 'Романенко Аріна';
        $data[] = 'Таранчук Анастасія';
        $data[] = 'Чернявська Софія';
        
        $data = ['num' => 1, 'list' => $data];
        
        $this->testAuth();
        $this->view->generate('group_view.php', $data);
    }
}