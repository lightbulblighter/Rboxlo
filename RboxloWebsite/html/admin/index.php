<?php 
	require_once("/var/www/backend/includes.php");
	
	if (!isset($_SESSION["user"]))
	{
		redirect("/login");
    }
    
    if ($_SESSION["user"]["rank"] <= 0)
    {
        require_once(file_build_path("html", "error", "403.php"));
        exit();
    }
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Admin");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
		?>

        <div class="container">
			<div class="row flex-center pt-5 mt-3">
				<div class="card" style="width: 40rem">
					<div class="card-header purple accent-3 white-text">
						Admin Panel
					</div>

                    <div class="card-body mx-4">

                        <?php if (INVITE_ONLY): ?>
                        <a href="/admin/invite">Generate invite key</a>
                        <?php endif; ?>

                    </div>

				</div>
			</div>
		</div>

		<?php
			build_footer();
		?>
	</body>
</html>