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

		<script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script type="text/javascript" src="/core/html/js/api.js" async defer></script>
		
		<script type="text/javascript">
			function form_verify()
			{
				$("#submit").attr("disabled", "disabled")

				<?php
					if (!isset($_GET["token"])):
				?>

				var information = {
					send: true,
					recaptcha: grecaptcha.getResponse(),
					csrf: "<?php echo($_SESSION["csrf"]); ?>"
				}

				<?php
					else:
				?>

				var information = {
					verification_key: $("#token").val(),
					recaptcha: grecaptcha.getResponse(),
					csrf: "<?php echo($_SESSION["csrf"]); ?>"
				}
				
				<?php
					endif;
				?>

				endpoint("/authentication/email_verify", "POST", information, (response) =>
				{
					toastr.options = {
						"closeButton": !response.success,
						"timeOut": response.success ? 2000 : 5000
					}

					toastr[response.success ? "success" : "error"](response.message, response.success ? "Success!" : "An error occurred.")

					if (response.success)
					{
						setTimeout(function()
						{
							location.replace("/my/dashboard")
						}, 3000)
					}
					else
					{
						$("#submit").removeAttr("disabled", "disabled")
					}
				})
			}
		</script>

		<div class="container">
			<?php if ($_SESSION["user"]["email_verified"]): ?>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Verify E-Mail</span>
				</div>
				<div class="card-body">
					<p>You are already verified on <?php echo(BASE_NAME); ?>!</p>
				</div>  
			</div>
			
			<br><br><br><br><br><br>

			<?php elseif (!isset($_GET["token"])): ?>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Verify E-Mail</span>
				</div>
				<div class="card-body">
					<p>In order to access some features on <?php echo(BASE_NAME); ?>, you need to verify your E-Mail address first. Some of those features include:</p>
					<ul>
						<li>Playing or creating <a href="/games">Games</a></li>
						<li>Shopping on the <a href="/catalog/">Catalog</a></li>
						<li>Posting on the <a href="/forums/">Forums</a></li>
						<li>Customizing your <a href="/my/character">Character</a></li>
					</ul>
					
					<p>
						Essentially, you literally cannot do anything on <?php echo(BASE_NAME); ?> without verifying your E-Mail address.
						<br><br>
						Fortunately for you, we at <?php echo(BASE_NAME); ?> have made the process of verifying your E-Mail address very simple. Just click that big purple button below, solve the captcha, and it'll send you a verification message to the E-Mail address you signed up with. Simply click on the link sent in that E-Mail, and you're done!
						<br><br>
						If you can't find the E-Mail in your main inbox, search for <code><?php echo(EMAIL_USERNAME); ?></code> in all inboxes or check your spam folder for incoming messages from <?php echo(BASE_NAME); ?>. If neither of those work, just re-send the E-Mail by clicking the purple button again.
					</p>

					<div align="center" class="mb-3">
						<div class="g-recaptcha" data-sitekey="<?php echo(RECAPTCHA_PUBLIC_KEY); ?>"></div>
					</div>

					<button class="btn purple-gradient btn-block" onclick="form_verify()" id="submit">Send verification E-Mail</button>
				</div>  
			</div>
			
			<?php elseif (!ctype_alnum($_GET["token"])): ?>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Verify E-Mail</span>
				</div>
				<div class="card-body">
					<p>
						Invalid verification token.
					</p>
				</div>
			</div>

			<br><br><br><br><br><br>
					
			<?php else: ?>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Verify E-Mail</span>
				</div>
				<div class="card-body">
					<p>
						Are you sure you want to verify your account, <?php echo($_SESSION["user"]["username"]) ?>, with the E-Mail <code><?php echo($_SESSION["user"]["email"]); ?></code> on <?php echo(BASE_NAME); ?>?
					</p>

					<div align="center" class="mb-3">
						<div class="g-recaptcha" data-sitekey="<?php echo(RECAPTCHA_PUBLIC_KEY); ?>"></div>
					</div>
					<input id="token" type="hidden" value="<?php echo($_GET["token"]); ?>">
					<button class="btn purple-gradient btn-block" onclick="form_verify()" id="submit">Yes, I am 100% sure of it</button>
				</div>  
			</div>

			<br><br><br><br><br><br>

			<?php endif; ?>
		</div>
		
		<?php
			build_footer();
		?>
	</body>
</html>