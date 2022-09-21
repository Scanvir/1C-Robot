<div class="container-fluid bg-projects" id="contacts">
    <div class="container pt-10 pb-20">
        <div class="part-header">
            <div class="h2 text-light mb-0 mt-0">Реєстрація</div>
            <div class="text-muted mb-0">Особистий кабінет</div>
        </div>

        <form method="post">

        <div class="part-content">
            <div class="row">
                <div class="cell-md-6 pl-0 pl-20-md">

                    <hr class="mt-6 mb-6">
                    <div>Після реєстрації ви отримаєте листа на електронну адресу, за допомогою якого, зможете підтвердити свою адресу та активувати ваш доступ до вебсайту</div>

                    <?php
                        if (!empty($data)) {
                    ?>
                    <hr class="mt-6 mb-6">
                    <?php
                            print_r('<div class="fg-red">'.$data['error'].'</div>');
                        }
                    ?>
                </div>
                <div class="cell-md-6">
                    <label class="mt-2 d-block">Прізвище та ім'я:</label>
                    <div class="input">
                        <input name="personName" type="text" pattern="[A-яіїєё']+\s[A-яіїєё']+" placeholder="Прізвище та ім'я" value="<?php if (isset($data['personName'])) print_r($data['personName']); ?>">
                        <div class="prepend">
                            <span class="mif-user"></span>
                        </div>
                    </div>

                    <label class="mt-2 d-block">Електронна пошта:</label>
                    <div class="input">
                        <input name="email" type="text" pattern="^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$" placeholder="Електронна пошта" value="<?php if (isset($data['email'])) print_r($data['email']); ?>">
                        <div class="prepend">
                            <span class="mif-envelop"></span>
                        </div>
                    </div>

                    <label class="mt-2 d-block">Пароль:</label>
                    <div class="input">
                        <input name="password" type="password" placeholder="Пароль">
                        <div class="prepend">
                            <span class="mif-key"></span>
                        </div>
                    </div>

                    <label class="mt-2 d-block">Підтвердження пароля:</label>
                    <div class="input">
                        <input name="passwordConfirm" type="password" placeholder="Підтвердження пароля">
                        <div class="prepend">
                            <span class="mif-key"></span>
                        </div>
                    </div>

                    <input type="hidden" name="register" value="1">

                    <div class="form-actions mt-4">
                        <button class="button secondary outline rounded" style='float: right;'>&nbsp;&nbsp;&nbsp;Зареєструватись&nbsp;&nbsp;&nbsp;</button>
                        <a href="/" style='float: left;'>&nbsp;&nbsp;&nbsp;На головну&nbsp;&nbsp;&nbsp;</a>
                    </div>
                </div>            </div>
        </div>
        </form>
    </div>
</div>