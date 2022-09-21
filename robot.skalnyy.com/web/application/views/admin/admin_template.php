<?php 
    include 'application/views/admin/header_admin.php';
?> 

<body >
<div class="pb-10 m-0">
    <section>
    <div class="container">
        <h1>Доброго дня, адміністратор!</h1>
        <h2>Вам доступні такі можливості:</h2>
        <div class="grid">
            <div class="row">
                <div class="cell-4">
                <ul>
                    <li><a href="/admin/branch">Оновлення аптек</a></li>
                    <li><a href="/admin/category">Оновлення категорій</a></li>
                    <li><a href="/admin/product">Оновлення товарів</a></li>
                    <li><a href="/admin/morion">Оновлення кодів Моріон</a></li>
                    <hr>
                    <li><a href="/admin/users">Зареєстровані Гості</a></li>
                    <li><a href="/admin/active">Активність</a></li>
                    <li><a href="/admin/photo">Фото товарів</a></li>
                    
                    <hr>
                    <li><a href="/">Перейти на сайт</a></li>
                </ul>
                </div>
<?php
    include 'application/views/'.$content_view;
    include 'application/views/admin/footer_admin.php'; 
