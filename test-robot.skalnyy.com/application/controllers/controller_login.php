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

            $user = $this->model->checkUserEmail($email);

            if (count($user) > 0) {
                $hash = $user[0]['password_hash'];
                $userID = $user[0]['id'];
                $active = $user[0]['active'];
                $activate_hash = $user[0]['activate_hash'];

                if (!$active) {
                    $mail = new Email();
                    $mail->setSubject('Activation')
                        ->setTextMessage("Ви успішно зареєструвались на сайті Школа онлайн. Для активації доступу перейдіть за посиланням 'https://school.skalnyy.com/register/activation/?GUID=$activate_hash'>.")
                        ->setHtmlMessage("Ви успішно зареєструвались на сайті Школа онлайн.<br>Для активації доступу перейдіть за посиланням <a href='https://school.skalnyy.com/register/activation/?GUID=$activate_hash'>Активація аккаунту</a>.")
                        ->addTo($_POST['email']);

                    $mail->send();

                    $data = array('error' => 'Ваш акаунт не активований. Перевірте електронну пошту, вам надіслано листа з активацією');
                    $this->view->generate('common/info_view.php', $data);
                    return;
                }

                $verify = $this->model->verifyHash($password, $hash);
                if ($verify) {
                    setcookie('auth', true, time()+3600*48, '/');
                    setcookie('userId', $userID, time()+3600*48, '/');
                    $host = 'https://' . $_SERVER['HTTP_HOST'];
                    header('HTTP/1.1 200');
                    header('Location:' . $host . '/home');
                } else {
                    $data = array('error' => 'Не вірний пароль,<br>спробуйте ще раз.');
                    $this->view->generate('common/info_view.php', $data);
                    return;
                }
            } else {
                $data = array('error' => 'Не вірна пошта чи пароль,<br>спробуйте ще раз.');
                $this->view->generate('common/info_view.php', $data);
                return;
            }
        }
        $this->view->generate('login/login_view.php');
    }
    function action_logout() {
        setcookie('auth', false, time()+3600, '/');
        $host = 'https://' . $_SERVER['HTTP_HOST'];
        header('HTTP/1.1 200');
        header('Location:' . $host . '/');
    }
    function action_resetpassword() {
        $data = array();
        if (!empty($_POST['email'])) {
            $email = $_POST['email'];

            $user = $this->model->checkUserEmail($email);

            if (count($user) > 0) {
                $personName = $user[0]['personName'];
                $userId = $user[0]['id'];
                $reset_hash = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

                $this->model->resetUserPassword($userId, $reset_hash);

                $mail = new Email();
                $mail->setSubject('Password recovery')
                    ->setTextMessage("На сайті Школа онлайн, запросили зміну пароля для облікового запису '$personName'. Для зміни пароля перейдіть за посиланням 'https://school.skalnyy.com/login/reset/?GUID=$reset_hash'>. Посилання буде дійсне протягом 2х годин.")
                    ->setHtmlMessage("На сайті Школа онлайн, запросили зміну пароля для облікового запису '$personName'.<br>Для зміни пароля перейдіть за посиланням <a href='https://school.skalnyy.com/login/reset/?GUID=$reset_hash'>Відновлення пароля</a>.<br>Посилання буде дійсне протягом 2х годин.")
                    ->addTo($email);

                $mail->send();

                $data = array('info' => "$personName, на вашу адресу $email надіслано листа з інструкцією");
                $this->view->generate('common/info_view.php', $data);
                return;
            } else if(!$this->model->checkEmail($email)) {
                $data = array('error' => 'Це взагалі не адреса електронної пошти');
            } else {
                $data = array('error' => 'Невідома адреса єлектронної пошти');
            }
        }
       $this->view->generate('login/resetpassword_view.php', $data);
    }
    function action_reset($get) {
        if (isset($get['GUID'])) {
            $reset_hash = $get['GUID'];
            $user = $this->model->checkResetHash($reset_hash);

            if (count($user) > 0) {
                $data = array('id' => $user[0]['id'], 'reset_hash' => $reset_hash);
                $this->view->generate('login/newpassword_view.php', $data);
            }
            else {
                $data = array('error' => 'Термін дії посилання закінчився');
                $this->view->generate('common/info_view.php', $data);
            }
            return;
        }
        $data = array('error' => 'Виникла помилка, будь ласка спробуйте ще раз.');
        $this->view->generate('common/info_view.php', $data);
    }
    function action_new() {
        if (!empty($_POST['confirmpassword']) && !empty($_POST['newpassword']) && !empty($_POST['reset_hash']) && !empty($_POST['id'])) {
            $newpassword = $_POST['newpassword'];
            $confirmpassword = $_POST['confirmpassword'];
            $reset_hash = $_POST['reset_hash'];
            $userId = $_POST['id'];

            if ($newpassword != $confirmpassword) {
                $data = array('error' => 'Паролі не співпадають', 'reset_hash' => $reset_hash, 'id' => $userId);
                $this->view->generate('login/newpassword_view.php', $data, array('GUID' => $reset_hash));
            } else if (!$this->model->checkPassword($newpassword)) {
                $data = array('error' => 'Пароль надто короткий', 'reset_hash' => $reset_hash, 'id' => $userId);
                $this->view->generate('login/newpassword_view.php', $data, array('GUID' => $reset_hash));
            } else {
                $this->model->updateUserPassword($userId, $this->model->generateHash($newpassword));
                $data = array('info' => 'Пароль змінено');
                $this->view->generate('common/info_view.php', $data);
            }
            return;
        }
        $data = array('error' => 'Виникла помилка, будь ласка спробуйте ще раз.');
        $this->view->generate('common/info_view.php', $data);
    }
    function action_register() {
        $data = []; $info = "";
        if (!empty($_POST['register'])){
            $error = false;
            if (!$this->model->checkName($_POST['personName'])){
                $info = $info . 'Вкажіть фамілію та ім\'я учня чи вчителя<br>';
                $error = true;
            }
            if (!$this->model->checkName($_POST['phone'])){
                $info = $info . 'Вкажіть номер телефона<br>';
                $error = true;
            }
            if (!$this->model->checkEmail(trim($_POST['email']))){
                $info = $info . 'Вкажіть електронну адресу, адреса повинна без пробілів на початку чи вкінці<br>';
                $error = true;
            }
            if (!$this->model->checkPassword($_POST['password'])){
                $info = $info . 'Пароль не достатньо складний<br>';
                $error = true;
            }
            if ($_POST['passwordConfirm'] != $_POST['password']){
                $info = $info . 'Паролі не співпадають<br>';
                $error = true;
            }

            $data = array(
                'error' => $info,
                'personName' => $_POST['personName'],
                'phone' => $_POST['phone'],
                'email' => trim($_POST['email']),
            );
            if (!$error){
                $reset_hash = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

                $this->model->register($_POST['personName'], $_POST['phone'], $_POST['email'], $this->model->generateHash($_POST['password']), $reset_hash);

                $mail = new Email();
                $mail->setSubject('Activation')
                    ->setTextMessage("Ви успішно зареєструвались на сайті Школа онлайн. Для активації доступу перейдіть за посиланням 'https://school.skalnyy.com/login/activation/?GUID=$reset_hash'>.")
                    ->setHtmlMessage("Ви успішно зареєструвались на сайті Школа онлайн.<br>Для активації доступу перейдіть за посиланням <a href='https://school.skalnyy.com/login/activation/?GUID=$reset_hash'>Активація аккаунту</a>.")
                    ->addTo($_POST['email']);

                $mail->send();

                $data = array('info' => 'Ви зареєстровані. Перевірте електронну пошту, вам надіслано листа з активацією');
                $this->view->generate('common/info_view.php', $data);
                return;
            }
        }
        $this->view->generate('login/register_view.php', $data);
    }
    function action_activation($get) {
        if (isset($get['GUID'])) {
            $activate_hash = $get['GUID'];
            $user = $this->model->checkActivateHash($activate_hash);

            if (count($user) > 0) {
                $this->model->activate($activate_hash);
                $data = array('info' => 'Ваш аккаунт успішно активовано');
                $this->view->generate('common/info_view.php', $data);
            } else {
                $data = array('error' => 'Помилка, код активації не вірний');
                $this->view->generate('common/info_view.php', $data);
            }
            return;
        }
        $data = array('error' => 'Виникла помилка, будь ласка спробуйте ще раз.');
        $this->view->generate('common/info_view.php', $data);
    }
}