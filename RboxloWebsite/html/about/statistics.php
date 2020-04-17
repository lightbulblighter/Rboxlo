<?php 
	require_once("/var/www/backend/includes.php");
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Statistics");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
        ?>  
        
        <script type="text/javascript" src="/core/html/js/api.js" async defer></script>

        <script type="text/javascript">
            window.setInterval(function()
            {
                endpoint("/statistics", "GET", null, function(response)
                {
                    $("#cpu").innerHTML(response.cpu)
                    $("#ram").innerHTML(response.ram)
                })
            }, 1000)
        </script>

        <div class="jumbotron card card-image" style="background-image: url(/core/html/img/about_backdrop.png)">
            <div class="text-white text-center">
                <div>
					<img src="/core/html/img/full.png" class="img-fluid" style="max-width: 600px">
					<br>
					<h1 class="card-title h1-responsive">Statistics</h1>
                </div>
            </div>
        </div>

		<div class="container">
			<div class="card">
                <?php
                    $statement = $GLOBALS["sql"]->prepare("SELECT COUNT(*) FROM `users`");
                    $statement->execute();
                    $users = $statement->fetchColumn();
                ?>
                
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Website statistics</span>
				</div>
				<div class="card-body mx-4">
					<p>
                        <h3>Numbers <h5>as of <?php echo(date("m/d/Y")); ?></h3>
                        <ul>
                            <li>There are currently <?php echo($users); ?> unique users registered on <?php echo(BASE_NAME); ?>.</li>
                        </ul>

                        <br><br>
                        
                        <h3>Website performance</h3>
                        <p>
                            Currently, the CPU load on the website is at <span id="cpu"></span>
                            <br>
                            Currently, the RAM usage on the website is at <span id="ram"></span>
                            <br><br>
                            Get more detailed website performance satistics at the <a href="/netdata/">official <?php echo(BASE_NAME); ?> netdata panel</a>.
                        </p>
					</p>
				</div>  
            </div>
        </div>

		<?php
			build_footer();
		?>
	</body>
</html>