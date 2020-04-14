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
			function share()
			{
				// TODO: Share
			}
		</script>

		<div class="container">
			<div class="card-columns d-flex justify-content-left">
				<div class="card card-body" style="max-width: 20rem;">
					<h4>Hello, <b><?php echo($_SESSION["user"]["username"]); ?></b>!</h4>
					<img src="<?php echo(get_thumbnail($_SESSION["user"]["id"], "user")); ?>" style="max-height:300px;">
				</div>

				<div class="w-100 ml-3">
					<div class="card card-body">
						<div class="card-block">
							<div class="md-form input-group m-0">
								<input type="text" class="form-control" placeholder="What are you up to?" aria-label="What are you up to?" aria-describedby="share" value="">
								<div class="input-group-append">
									<button class="btn btn-md btn-purple m-0 px-3" type="button" id="share">Share</button>
								</div>
							</div>
						</div>
					</div>

					<div class="card card-body">
						<div class="card-block">
							<h4 class="card-title">My Feed</h4>
							<?php
								// TODO: Feed
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php
			build_footer();
		?>
	</body>
</html>
