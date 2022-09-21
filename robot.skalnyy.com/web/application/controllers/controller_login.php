<?php

class Controller_Login extends Controller {

    public function __construct()
    {
        $this->model = new Model_Login();
        $this->view = new View();
    }

    function action_index() {
        if (!empty($_POST['email']) && !empty($_POST['password'])){
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            if(!$this->model->checkEmail($email)) {
                $data = array('header' => 'Помилка авторизації', 'error' => 'Помилка, спробуйте ще раз');
                    $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                return;
            } else {

                $user = User::checkUserEmail($email);
    
                if (count($user) > 0) {
                    $hash           = $user[0]['password_hash'];
                    $userID         = $user[0]['id'];
                    $active         = $user[0]['active'];
                    $activate_hash  = $user[0]['activate_hash'];
                    
                    $error_login    = $user[0]['error_login'];
                    $blocked        = $user[0]['blocked'];

                    if (!$active) {
                        $mail = new Email();
                        $mail->setSubject('Activation')
                            ->setTextMessage("Ви успішно зареєструвались на сайті 1С:Робот. Для активації доступу перейдіть за посиланням 'https://robot.skalnyy.com/login/activation/?GUID=$activate_hash'>.")
                            ->setHtmlMessage("Ви успішно зареєструвались на сайті 1С:Робот.<br>Для активації доступу перейдіть за посиланням <a href='https://robot.skalnyy.com/login/activation/?GUID=$activate_hash'>Активація аккаунту</a>.")
                            ->addTo($_POST['email']);
    
                        $mail->send();
    
                        $data = array('header' => 'Авторизація : Акаунт не активований', 'error' => 'Ваш акаунт не активований. Перевірте електронну пошту, вам надіслано листа з активацією');
                        $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                        return;
                    }
                    
                    if ($blocked == 0) {
                        $verify = User::verifyHash($userID, $password, $hash, $error_login);
                        if ($verify) {
                            setcookie('auth', true, time() + 3600 * 24 * 10, '/');
                            setcookie('userId', $userID, time() + 3600 * 24 * 10, '/');
                            $host = 'https://' . $_SERVER['HTTP_HOST'];
                            header('HTTP/1.1 200');
                            header('Location:' . $host . '/');
                        } else {
                            $data = array('header' => 'Помилка авторизації', 'error' => 'Помилка, спробуйте ще раз');
                            $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                            return;
                        }
                    } else {
                        $data = array('header' => 'Помилка авторизації', 'error' => 'Ви ввели невірний пароль більше допустимої кількості разів, аккаунт заблокований на 30 хвилин!');
                            $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                        return;
                    }
                } else {
                    $data = array('header' => 'Помилка авторизації', 'error' => 'Помилка, спробуйте ще раз');
                        $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                    return;
                }
            }
        }
        $data = ['header' => 'Авторизація'];
        $this->view->generate('login/login_view.php', 'login/template_view.php', $data);
    }
    function action_logout() {
        setcookie('auth', false, time()+3600, '/');
        setcookie('userId', 0, time()+3600, '/');
        $host = 'https://' . $_SERVER['HTTP_HOST'];
        header('HTTP/1.1 200');
        header('Location:' . $host . '/');
    }
    function action_resetpassword() {
        $data = ['header' => 'Відновлення пароля', 'info' => ''];
        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            
            if(!$this->model->checkEmail($email)) {
                $data = array('header' => 'Помилка відновлення пароля', 'error' => 'Помилка, спробуйте ще раз');
                    $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                    return;
            } else {
                $user = User::checkUserEmail($email);

                if (count($user) > 0) {
                    $personName = $user[0]['personName'];
                    $userId = $user[0]['id'];
                    $reset_hash = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    
                    $this->model->resetUserPassword($userId, $reset_hash);
    
                    $mail = new Email();
                    $mail->setSubject('Password recovery')
                        ->setTextMessage("На сайті 1С:Робот, запросили зміну пароля для облікового запису '$personName'. Для зміни пароля перейдіть за посиланням 'https://robot.skalnyy.com/login/reset/?GUID=$reset_hash'>. Посилання буде дійсне протягом 2х годин.")
                        ->setHtmlMessage("На сайті 1С:Робот, запросили зміну пароля для облікового запису '$personName'.<br>Для зміни пароля перейдіть за посиланням <a href='https://robot.skalnyy.com/login/reset/?GUID=$reset_hash'>Відновлення пароля</a>.<br>Посилання буде дійсне протягом 2х годин.")
                        ->addTo($email);
    
                    $mail->send();
    
                    $data = array('header' => 'Відновлення пароля', 'info' => "$personName, на вашу адресу $email надіслано листа з інструкцією");
                    $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                    return;
                
                } else {
                    $data = array('header' => 'Помилка відновлення пароля', 'error' => 'Помилка, спробуйте ще раз');
                    $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                    return;
                }
            }
        }
        $this->view->generate('login/resetpassword_view.php', 'login/template_view.php', $data);
    }
    function action_reset($get) {
        if (isset($get['GUID'])) {
            $reset_hash = $get['GUID'];
            $user = $this->model->checkResetHash($reset_hash);

            if (count($user) > 0) {
                $data = array('header' => 'Новий пароль', 'id' => $user[0]['id'], 'reset_hash' => $reset_hash);
                $this->view->generate('login/newpassword_view.php', 'login/template_view.php', $data);
            }
            else {
                $data = array('header' => 'Новий пароль', 'error' => 'Термін дії посилання закінчився');
                $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
            }
            return;
        }
        $data = array('header' => 'Новий пароль', 'error' => 'Виникла помилка, будь ласка спробуйте ще раз.');
        $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
    }
    function action_new() {
        if (!empty($_POST['confirmpassword']) && !empty($_POST['newpassword']) && !empty($_POST['reset_hash']) && !empty($_POST['id'])) {
            $newpassword = $_POST['newpassword'];
            $confirmpassword = $_POST['confirmpassword'];
            $reset_hash = $_POST['reset_hash'];
            $userId = $_POST['id'];

            if ($newpassword != $confirmpassword) {
                $data = array('header' => 'Помилка - Новий пароль', 'error' => 'Паролі не співпадають', 'reset_hash' => $reset_hash, 'id' => $userId);
                $this->view->generate('login/newpassword_view.php', 'login/template_view.php', $data, array('GUID' => $reset_hash));
            } else if (!$this->model->checkPassword($newpassword)) {
                $data = array('header' => 'Помилка - Новий пароль', 'error' => 'Пароль не достатньо складний (довжина не меньше 8 символів, обовязково цифри, великі та маленькі латинські літери)', 'reset_hash' => $reset_hash, 'id' => $userId);
                $this->view->generate('login/newpassword_view.php', 'login/template_view.php', $data, array('GUID' => $reset_hash));
            } else {
                User::updateUserPassword($userId, User::generateHash($newpassword));
                $data = array('header' => 'Новий пароль', 'info' => 'Пароль змінено');
                $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
            }
            return;
        }
        $data = array('header' => 'Помилка - Новий пароль', 'error' => 'Виникла помилка, будь ласка спробуйте ще раз.');
        $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
    }
    function action_register() {
        $data = ['header' => 'Реєстрація', 'error' => ''];
        if (!empty($_POST['register'])){
            $data = '';
            $error = false;
            
            $user = User::checkUserEmail($_POST['email']);
    
            if (count($user) > 0) {
                $data = $data . 'Гість з таким email вже зареєстрований<br>';
                $error = true;
            }
            if (!$this->model->checkName($_POST['personName'])){
                $data = $data . 'Вкажіть ім\'я та прізвище<br>';
                $error = true;
            }
            if (!$this->model->checkEmail($_POST['email'])){
                $data = $data . 'Вкажіть електронну адресу<br>';
                $error = true;
            }
            if (!$this->model->checkPassword($_POST['password'])){
                $data = $data . 'Пароль не достатньо складний (довжина не меньше 8 символів, обовязково цифри, великі та маленькі латинські літери)<br>';
                $error = true;
            }
            if ($_POST['passwordConfirm'] != $_POST['password']){
                $data = $data . 'Паролі не співпадають<br>';
                $error = true;
            }

            $data = array(
                'header' => 'Реєстрація', 
                'error' => $data,
                'personName' => $_POST['personName'],
                'email' => $_POST['email'],
            );
            if (!$error){
                $reset_hash = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

                $this->model->register($_POST['personName'], $_POST['email'], User::generateHash($_POST['password']), $reset_hash);

                $mail = new Email();
                $mail->setSubject('Activation')
                    ->setTextMessage("Ви успішно зареєструвались на сайті 1С:Робот. Для активації доступу перейдіть за посиланням 'https://robot.skalnyy.com/login/activation/?GUID=$reset_hash'>.")
                    ->setHtmlMessage("Ви успішно зареєструвались на сайті 1С:Робот.<br>Для активації доступу перейдіть за посиланням <a href='https://robot.skalnyy.com/login/activation/?GUID=$reset_hash'>Активація аккаунту</a>.")
                    ->addTo($_POST['email']);

                $mail->send();

                $data = array('header' => 'Реєстрація успішна', 'info' => 'Ви зареєстровані. Перевірте електронну пошту, вам надіслано листа з активацією');
                $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
                return;
            }
        }
        $this->view->generate('login/register_view.php', 'login/template_view.php', $data);
    }
    function action_activation($get) {
        if (isset($get['GUID'])) {
            $activate_hash = $get['GUID'];
            $user = $this->model->checkActivateHash($activate_hash);

            if (count($user) > 0) {
                $this->model->activate($activate_hash);
                $data = array('header' => 'Активація успішна', 'info' => '<div class="RegistrationSuccessful">Ваш аккаунт успішно активовано</div>');
                $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
            } else {
                $data = array('header' => 'Активація помилка', 'error' => 'Помилка, код активації не вірний');
                $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
            }
            return;
        }
        $data = array('header' => 'Активація помилка', 'error' => 'Виникла помилка, будь ласка спробуйте ще раз.');
        $this->view->generate('common/info_view.php', 'login/template_view.php', $data);
    }
}