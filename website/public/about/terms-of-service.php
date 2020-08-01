<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Terms of Service");
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
					<h1 class="card-title h1-responsive">Terms of Service</h1>
                </div>
            </div>
        </div>

		<div class="container">
			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Terms of Service</span>
				</div>
				<div class="card-body">
					<p>
                        At <?= PROJECT["NAME"] ?>, we expect you to follow a terms of service and a basic set of rules in order to use this website. The terms are as follows:

                        <ul>
                            <li><b>You must be 13 years old or older</b> to use <?= PROJECT["NAME"] ?> or when creating an account.</li>
                            <li><b>Do not spam the website with invalid requests.</b> This is also known as a causing a <a href="https://en.wikipedia.org/wiki/Denial-of-service_attack">denial of service attack</a> on a website. Don't.</li>
                            <li><b>Don't create a new account upon moderation.</b> Your ban will eventually expire. This is known as ban evasion.</li>
                            <li><b>No flaming / bullying.</b> If you have an honest critique with someone, there are better ways of expressing your frustrations than yelling at them through text.</li>
                            <li><b>No off-site deals.</b> You cannot and should not use real money or any other things to exchange Rbux with.</li>
                            <li><b>Don't use slurs.</b> The definition of slur is extensive, but should you question if it's a slur or not, it's probably not allowed.</li>
                            <li><b>No NSFW content.</b> Please. This means stuff such as pornographic content, gore, yiff, lewd content, etc.</li>
							<li><b>Don't be a jerk.</b> Be a kind person.</li>
							<li><b>Do not sexualize minors.</b> Even as a joke, doing so is disgusting, and is absolutely forbidden. <b>This is a no tolerance policy.</b></li>
							<li><b>Do not remove the Watchdog system.</b> Watchdog is only used in rare scenarios where an admin cannot join a game to moderate.</li>
                        </ul>
					</p>
				</div>  
			</div>

			<br><br><br><br><br><br>
		</div>

		<?php
			build_footer();
		?>
	</body>
</html>