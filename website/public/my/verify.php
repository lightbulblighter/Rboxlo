<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment/email.environment.php");

	if (!isset($_SESSION["user"]))
	{
		redirect("/login");
	}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Verify E-Mail");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
		?>

		<script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script type="text/javascript">
			function submitVerify()
			{
				$("#submit").attr("disabled", "disabled")

				<?php
					if (!isset($_GET["token"])):
				?>

				var information = {
					send: true,
					recaptcha: grecaptcha.getResponse(),
					csrf: "<?= $_SESSION["csrf"] ?>"
				}

				<?php
					else:
				?>

				var information = {
					token: $("#token").val(),
					recaptcha: grecaptcha.getResponse(),
					csrf: "<?= $_SESSION["csrf"] ?>"
				}
				
				<?php
					endif;
				?>

				endpoint("/authentication/verification/email", "POST", information, (response) =>
				{
					toastr.options = {
						"closeButton": !response.success,
						"timeOut": response.success ? 2000 : 5000
					}

					toastr[response.success ? "success" : "error"](response.message, response.success ? "Success!" : "An error occurred.")

					<?php
						if (isset($_GET["token"])):
					?>
					
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

					<?php
						else:
					?>

					$("#submit").removeAttr("disabled", "disabled")

					<?php
						endif;
					?>
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
					<p>Your E-Mail address is already verified on <?= PROJECT["NAME"] ?>!</p>
				</div>  
			</div>
			
			<br><br><br><br><br><br>

			<?php elseif (!isset($_GET["token"])): ?>

			<div class="card">
				<div class="rounded-top mdb-color purple accent-3 pt-3 pl-3 pb-3">
					<span class="white-text">Verify E-Mail</span>
				</div>
				<div class="card-body">
					<p>In order to access some features on <?= PROJECT["NAME"] ?>, you need to verify your E-Mail address first. Some of those features include:</p>
					<ul>
						<li>Playing or creating <a href="/games">Games</a></li>
						<li>Shopping on the <a href="/catalog/">Catalog</a></li>
						<li>Posting on the <a href="/forums/">Forums</a></li>
						<li>Customizing your <a href="/my/character">Character</a></li>
					</ul>
					
					<p>
						Essentially, you literally cannot do anything on <?= PROJECT["NAME"] ?> without verifying your E-Mail address.
						<br><br>
						Fortunately for you, we at <?= PROJECT["NAME"] ?> have made the process of verifying your E-Mail address very simple. Just click that big purple button below, solve the captcha, and it'll send you a verification message to the E-Mail address you signed up with. Simply click on the link sent in that E-Mail, and you're done!
						<br><br>
						If you can't find the E-Mail in your main inbox, search for <?= EMAIL["ADDRESS"] ?> in all inboxes or check your spam folder for incoming messages from <?= ENVIRONMENT["PROJECT"]["NAME"] ?>. If neither of those work, just re-send the E-Mail by clicking the purple button again.
					</p>

					<br>

					<div class="g-recaptcha" data-sitekey="<?= GOOGLE["RECAPTCHA"]["PUBLIC_KEY"] ?>" data-size="invisible"></div>
					<button class="btn purple-gradient btn-block" onclick="submitVerify()" id="submit">Send verification E-Mail</button>
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
						Are you sure you want to verify your account, <?= $_SESSION["user"]["username"] ?>, with the E-Mail <code><?= $_SESSION["user"]["email"] ?></code> on <?= PROJECT["NAME"] ?>?
					</p>

					<div class="g-recaptcha" data-sitekey="<?= GOOGLE["RECAPTCHA"]["PUBLIC_KEY"] ?>" data-size="invisible"></div>
					<input id="token" type="hidden" value="<?= $_GET["token"] ?>">
					<button class="btn purple-gradient btn-block" onclick="submitVerify()" id="submit">Yes, I am 100% sure of it</button>
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