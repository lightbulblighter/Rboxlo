<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");

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
				<div class="card card-body" style="max-width: 20rem">
					<h4 class="text-truncate text-center text-fluid">Hello, <b><?php echo($_SESSION["user"]["username"]); ?></b>!</h4>
					<hr class="mt-0">
					<img class="img-fluid" src="/html/img/thumbnails/users/<?php echo($_SESSION["user"]["id"]); ?>.png" style="width: 300px">
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
							<hr>

							<?php
								//// This only fetches from official announcements for now.
								//$statement = $GLOBALS["sql"]->prepare("SELECT * FROM `feed` WHERE `official` = 1");
								//$statement->execute();
								//
								//foreach ($statement as $result)
								//{
								//	// This isn't finished.
								//}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<br><br><br>
		
		<?php
			build_footer();
		?>
	</body>
</html>
