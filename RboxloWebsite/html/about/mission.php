<?php 
	require_once("/var/www/backend/includes.php");
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Landing");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
    	?>  

        <div class="jumbotron card card-image" style="background-image: url(/core/html/img/about_backdrop.png)">
            <div class="text-white text-center">
                <div>
					<img src="/core/html/img/full.png" class="img-fluid" style="max-width: 600px">
					<br>
					<h1 class="card-title h1-responsive">About Us</h1>
                </div>
            </div>
        </div>

		<?php
			build_footer();
		?>
	</body>
</html>