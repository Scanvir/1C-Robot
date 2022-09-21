<?php 
    include 'application/views/layouts/header.php';
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
	@media only screen and (max-width: 550px) {
	  [class*="login-form"] {
		width: 90%;
	  }
    }
</style>

<body class="h-vh-100">

<?php
    include 'application/views/'.$content_view;
    include 'application/views/layouts/footer.php'; 
