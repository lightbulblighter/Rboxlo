<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");

	if (!isset($_SESSION["user"]))
	{
		redirect("/login");
	}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Games");
		?>
	</head>
	<body>
		<?php
			build_navigation_bar();
		?>

        <div class="container">
			<ul class="nav nav-tabs nav-justified md-tabs purple accent-3" id="gamesTab" role="tablist">
				<li class="nav-item waves-effect waves-light">
					<a class="nav-link active" id="places-tab" data-toggle="tab" href="#places" role="tab" aria-controls="places" aria-selected="true">Places</a>
				</li>
				
				<li class="nav-item waves-effect waves-light">
					<a class="nav-link" id="servers-tab" data-toggle="tab" href="#servers" role="tab" aria-controls="servers" aria-selected="false">Servers</a>
				</li>
			</ul>
			
			<div class="tab-content card pt-5" id="gamesTabContent">
				<div class="tab-pane fade show active" id="places" role="tabpanel" aria-labelledby="places-tab">
					<div class="md-form input-group m-0">
						<input type="text" class="form-control" placeholder="Search places" aria-label="Search places" aria-describedby="places-search" value="">
						<div class="input-group-append">
							<button class="btn btn-md btn-purple purple accent-3 m-0 px-3" type="button" id="places-search">Search</button>
						</div>
					</div>
				</div>


				<div class="tab-pane fade" id="servers" role="tabpanel" aria-labelledby="servers-tab">
					<p>Servers!</p>
				</div>
			</div>
		</div>

		<?php
			build_footer();
		?>

		<script type="text/javascript" src="<?= get_server_host() ?>/html/js/games.min.js" async defer></script>
	</body>
</html>
