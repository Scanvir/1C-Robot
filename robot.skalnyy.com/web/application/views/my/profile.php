<?php
    $profile = $data['profile'];
    $error = '';
    if(!empty($data['error']))
        $error = '<p class="remark alert">'.$data['error'].'</p>';
?>
<div class="part-content">
    <div class="row">
        <div class="cell-md-6">
            <p class="remark success">Зміна персональних даних</p>
            <form method="post">
            <label class="mt-2 d-block">Прізвище та ім'я:</label>
            <div class="input">
                <input name="personName" type="text" pattern="[A-яіїєё']+\s[A-яіїєё']+" placeholder="Прізвище та ім'я" value="<?php if (isset($profile['personName'])) print_r($profile['personName']); ?>">
                <div class="prepend">
                    <span class="mif-user"></span>
                </div>
            </div>

            <label class="mt-2 d-block">Електронна пошта:</label>
            <div class="input">
                <input disabled name="email" type="text" pattern="^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$" placeholder="Електронна пошта" value="<?php if (isset($profile['email'])) print_r($profile['email']); ?>">
                <div class="prepend">
                    <span class="mif-envelop"></span>
                </div>
            </div>
            
            <input type="hidden" name="saveProfile" value="1">

            <div class="form-actions mt-4">
                <button class="button secondary outline rounded" style='float: right;'>&nbsp;&nbsp;&nbsp;Зберегти&nbsp;&nbsp;&nbsp;</button>
            </div>
            </form>
        </div>            
        
        <div class="cell-md-6">
            <p class="remark success">Зміна пароля</p>
            <?php echo $error; ?>
            <form method="post">
                
            <label class="mt-2 d-block">Діючий пароль:</label>
            <div class="input">
                <input name="passwordOld" type="password" placeholder="Діючий пароль">
                <div class="prepend">
                    <span class="mif-key"></span>
                </div>
            </div>
            
            <label class="mt-2 d-block">Новий пароль:</label>
            <div class="input">
                <input name="password" type="password" placeholder="Новий пароль">
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

            <input type="hidden" name="savePassword" value="1">

            <div class="form-actions mt-4">
                <button class="button secondary outline rounded" style='float: right;'>&nbsp;&nbsp;&nbsp;Зберегти&nbsp;&nbsp;&nbsp;</button>
            </div>
            </form>
            
        </div>
    </div>
</div>