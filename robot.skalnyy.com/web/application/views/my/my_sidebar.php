<ul class="sidebar-menu ">
	<li class="group-title">Особистий кабінет</li>
	<li><a href="/my/profile">Мій профіль</a></li>
	<?php 
		if (User::isAdmin()){
			print('<li><a href="/admin">Адмінка</a></li>');
		}
	?>
</ul>
