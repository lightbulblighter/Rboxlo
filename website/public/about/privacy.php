<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Privacy");
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
					<h1 class="card-title h1-responsive">Privacy</h1>
                </div>
            </div>
        </div>

		<div class="container">
			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Privacy Policy</span>
				</div>
				<div class="card-body mx-4">
                    <p>
                        At <?= PROJECT["NAME"] ?>, we're dedicated to protecting our users privacy wherever possible.

						LOLNO
                    </p>
				</div>  
            </div>
        </div>

		<?php
			build_footer();
		?>
	</body>
</html>