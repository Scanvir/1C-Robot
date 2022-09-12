<?php include 'application/views/layouts/header.php'; ?>
<style>
	.center {
		width: 50%;
		top: 50%;
		margin: auto;
		font-weight: 200!important;
		font-size: 3rem;
		text-align: center;

	}
	.red {
		color: red;
	}
	body {
		height: 100%;
	}
</style>
<body>
    <?php
        if($data) {
            if (array_key_exists('error', $data)) {
                echo '<div class="center red">';
                echo $data['error'];
            } else {
                echo '<div class="center">';
                echo $data['info'];
            }
        }
    ?><br>
    <button onclick="document.location='/'" class="button secondary outline rounded large">&nbsp;&nbsp;&nbsp;На головну&nbsp;&nbsp;&nbsp;</button>
    </div>
<?php include 'application/views/layouts/footer.php'; ?>