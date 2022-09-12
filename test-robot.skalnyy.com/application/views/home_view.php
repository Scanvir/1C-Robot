<?php include 'application/views/layouts/header.php'; ?>
<body class="container-fluid h-vh-100">
<?php //include 'application/views/layouts/menu.php'; ?>
    <div id="content-wrapper" class="content-inner h-100" style="overflow-y: auto">
		<?php
			if (array_key_exists('0', $data)) {
                include 'application/views/school/lesson.php';
            } else {
                include 'application/views/school/index.php';
            }
		?>
    </div>
<?php include 'application/views/layouts/footer.php'; ?>