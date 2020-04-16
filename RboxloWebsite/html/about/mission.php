<?php 
	require_once("/var/www/backend/includes.php");
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("About Us");
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

		<div class="container">
			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">What is this?</span>
				</div>
				<div class="card-body">
					<p></p>
				</div>  
			</div>

			<br><br>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">What is our goal?</span>
				</div>
				<div class="card-body">
					<p></p>
				</div>  
			</div>

			<br><br>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Who made this?</span>
				</div>
				<div class="card-body">
					<p></p>
				</div>  
			</div>
		</div>

		<?php
			build_footer();
		?>
	</body>
</html>