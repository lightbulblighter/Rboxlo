<?php 
	require_once("/var/www/backend/includes.php");

	if (!isset($_SESSION["user"]))
	{
		redirect("/login");
	}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Dashboard");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
		?>
		
		<script>
			function form_verify()
			{
				
			}
		</script>

		<div class="container">
			<div class="card card-body" style="max-width: 20rem;">

			</div>
		</div>
		
		<?php
			build_footer();
		?>
	</body>
</html>
