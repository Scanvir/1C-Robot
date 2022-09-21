<?php

class Controller_Search extends Controller {

    public function __construct() {
        $this->view = new View();
    }

    function action_index() {
        $data = array('header' => 'Пошук', 'view' => 'index');
        $this->view->generate('search/search.php', 'search/template.php', $data);
    }
    function action_search() {
        $userId = (int)$_COOKIE['userId'];
        User::activeUser($userId);
        
        $search = $_POST['search'];
        if ($search == ''){
            header('HTTP/1.1 200');
            header('Location:' . $host . '/search');
        }
            
        $data = array('header' => 'Пошук', 'view' => 'search', 'search' => $search, 'result' => Multisearch::search($search));
        $this->view->generate('search/search.php', 'search/template.php', $data);
    }
    function action_product($get) {
        $userID = (int)$_COOKIE['userId'];
        $code = substr($get['code'], 0, 10);
        $product = Product::getProductByCode($code, $userID);
        $data = array('header' => $product['Name'], 'view' => 'product', 'product' => $product, 'result' => Product::getRests($code));
        $this->view->generate('search/search.php', 'search/template.php', $data);
    }
    function action_instruction($get) {
        $code = substr($get['Code'], 0, 10);
        $data = Morion::GetInstruction($code);
        if(is_array($data))
            if(array_key_exists('info_html_ukr', $data)){
                $data = base64_decode($data['info_html_ukr'], true);
            }

        $this->view->generate('search/instruction.php', null, $data);
    }
}