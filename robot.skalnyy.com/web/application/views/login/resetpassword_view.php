<?php
defined("DIRSEP") OR exit('А-та-та!');
?>
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
<form class="login-form" method="post">
    <h2 class="text-light" style='text-align: center'>Для відновлення пароля вкажіть адресу електронної пошти</h2>
    <div class="form-group">
        <input type="text" data-role="input" pattern="^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$"  data-prepend="<span class='mif-envelop' style='color: #74489d; '>" placeholder="Адреса пошти:" name="email">
    </div>
    <div class="form-group">
        <button class="button secondary outline rounded" style='float: right;'>Відновити...</button>
        <a href="/" style='float: left;'>&nbsp;&nbsp;&nbsp;На головну&nbsp;&nbsp;&nbsp;</a>
    </div>

</form>
