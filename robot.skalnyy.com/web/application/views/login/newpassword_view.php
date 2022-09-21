    <style>
        .login-form {
            width: 500px;
            top: 50%;
            margin-left: auto;
            margin-right: auto;
            margin-top: -200px;
        }
        body {
            height: 100%;
        }
    </style>
</head>
<body class="h-vh-100">
    <form class="login-form" action="/login/new/" method="post">
        <h2 class="text-light" style='text-align: center'>Зміна пароля</h2>
        <div class="form-group">
            <input type="password" data-role="input" data-prepend="<span class='mif-envelop' style='color: #74489d; '>" placeholder="Новий пароль:" name="newpassword">
        </div>
        <div class="form-group">
            <input type="password" data-role="input" data-prepend="<span class='mif-envelop' style='color: #74489d; '>" placeholder="Підтвердіть новий пароль:" name="confirmpassword">
        </div>
    <?php
        if($data) {
            if(array_key_exists('error', $data)){
                $color = 'red';
                $value = $data['error'];
            }
            else if(array_key_exists('info', $data)){
                $color = 'green';
                $value = $data['info'];
            }
            else {
                $color = 'white';
                $value = '';
            }
            ?>
            <div class="form-group">
                <div class="bg-<?php echo $color; ?> fg-white rounded">&nbsp;<?php echo $value; ?></div>
            </div>
            <?php
        }
        ?>
        <div class="form-group">
            <button class="button secondary outline rounded" style='float: right;'>Відновити...</button>
            <a href="/" style='float: left;'>&nbsp;&nbsp;&nbsp;На головну&nbsp;&nbsp;&nbsp;</a>
        </div>
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <input type="hidden" name="reset_hash" value="<?php echo $data['reset_hash']; ?>">
    </form>