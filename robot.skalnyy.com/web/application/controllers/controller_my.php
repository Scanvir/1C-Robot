<?php

class Controller_My extends Controller {

    public function __construct() {
        $this->view = new View();
        if (!User::isGuest()){
            header('HTTP/1.1 200');
            header('Location:' . $host . '/login');
        }
        
    }
    function action_index() {
        $userId = (int)$_COOKIE['userId'];
        User::activeUser($userId);
        
        $data = ['header' => 'Особистий кабінет', 'view' => 'index'];
        $this->view->generate('my/cart.php', 'my/template.php', $data);
    }
    function action_cart() {
        $cart = Cart::getCart();

        $data = ['header' => 'Кошик', 'view' => 'cart', 'cart' => $cart];
        $this->view->generate('my/cart.php', 'my/template.php', $data);
    }
    function action_reserve() {
        $userId = (int)$_COOKIE['userId'];
        $reserve = Cart::ReserveFromDB($userId);

        $data = ['header' => 'Мої замовлення', 'view' => 'reserve', 'reserve' => $reserve];
        $this->view->generate('my/reserve.php', 'my/template.php', $data);
    }
    function action_profile() {
        $userId = (int)$_COOKIE['userId'];
        $profile = User::profile($userId);
        $error = '';

        if (!empty($_POST['saveProfile'])) {
            $personName = $_POST['personName'];
            User::updateProfile($userId, $personName);
        } else if (!empty($_POST['savePassword'])) {
            $passwordOld = $_POST['passwordOld'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['passwordConfirm'];
            
            if ($password != $passwordConfirm) {
                $error = 'Помилка, спробуйте ще раз';
            } else if (!User::checkPassword($password)){
                $error = 'Пароль недостатньо складний';
            } else {
                $user = User::checkUserEmail($profile['email']);
                $verify = User::verifyHash($userId, $passwordOld, $user[0]['password_hash']);
                if (!$verify) {
                    $error = 'Помилка, спробуйте ще раз';
                } else {
                    User::updateUserPassword($userId, User::generateHash($password));
                    $data = array('header' => 'Новий пароль', 'error' => 'Пароль успішно змінено.');
                    $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                    return;
                }
            }
        }
        
        $profile = User::profile($userId);
        $data = ['header' => 'Мій профіль', 'view' => 'profile', 'profile' => $profile, 'error' => $error];
        $this->view->generate('my/profile.php', 'my/template.php', $data);
    }
}