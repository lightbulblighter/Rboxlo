<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
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

        <div class="jumbotron card card-image" style="background-image: url(/html/img/backdrops/about.png)">
            <div class="text-white text-center">
                <div>
					<img src="/html/img/logos/2016/full.png" class="img-fluid" style="width: 600px">
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
					<p>
						<?= PROJECT["NAME"] ?> is a 100% transparent, 100% open source Roblox private server. We want to recreate the fun of playing Roblox from all client versions 2007 through 2017
						without imposing restrictions on developers, users, or anybody who wishes to play <?= PROJECT["NAME"] ?>.
						<br><br>
						We accomplish this in several ways:
						<ul>
							<li>No profanity filter</li>
							<li>Only users 13+ may play, so as to create a more mature community</li>
							<li>Allowing games to have blood/gore without them being banned</li>
							<li>Being open source so people can contribute, fix, solve issues, and develop for <?= PROJECT["NAME"] ?></li>
							<li>Maintaining 100% transparency in what staff members do (see staff audits <a href="/audits/">here</a>)</li>
						</ul>
						
						<?= PROJECT["NAME"] ?> is created with the sense of transparency at it's heart, after many other Roblox private servers did not uphold transparency fully.
					</p>
				</div>  
			</div>

			<br><br>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">What is our mission?</span>
				</div>
				<div class="card-body">
					<p>
						Our goal is to be as transparent and open source as possible and not creating any doubt in newcomers or users of <?= PROJECT["NAME"] ?>.
						<br><br>
						Most Roblox private servers opt to be closed-source, and very opaque so they can admin abuse and treat the community very poorly; and, as we have seen with those private servers, the community manifests
						into a savage beast that requires a huge staff team (sometimes more than 20 people!) to tame without getting hurt.
						<br><br>
						We would also like to teach users how to program using PHP, JavaScript, ASM, or other languages by open sourcing all the works that we have created.
					</p>
				</div>  
			</div>

			<br><br>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Who made this?</span>
				</div>
				<div class="card-body">
					<p>
						<?= PROJECT["NAME"] ?> is created by a team of young, aspiring developers of whose contributions you can see on the <a href="<?= REPOSITORY["URL"] ?>/graphs/contributors">GitHub contributors page</a>.
						<br><br>
						You can contribute too! Create a pull request, solve an issue, or add a new feature; these are the beauties of being open source.
					</p>
				</div>  
			</div>
		</div>

		<?php
			build_footer();
		?>
	</body>
</html>