<?php 
	require_once("/var/www/backend/includes.php");

	if (isset($_SESSION["user"]))
	{
		redirect("/my/dashboard");
	}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Sign Up");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
		?>

		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script src="/core/html/js/authentication.js" async defer></script>

		<script type="text/javascript">
			function form_register()
			{
				var information = {
					username: $("#username").val(),
					password: $("#password").val(),
					email: $("#email").val(),
					confirmed_password: $("#confirmed_password").val(),
					recaptcha: grecaptcha.getResponse(),
					<?php 
						if (INVITE_ONLY):
					?>
					invite_key: $("#invite_key").val(),
					<?php
						endif;
					?>
					csrf: "<?php echo($_SESSION["csrf"]); ?>"
				}

				register(information, function(response)
				{
					toastr.options = {
						"closeButton": !status,
						"timeOut": response["success"] ? 2000 : 5000
					}

					toastr[response["success"] ? "success" : "error"](response["message"], response["success"] ? "Success!" : "An error occurred.")

					if (response["success"])
					{
						setTimeout(function()
						{
							location.replace("/my/dashboard")
						}, 2000)
					}
					else
					{
						$("#submit").removeAttr("disabled", "disabled")
						$("#13confirm").removeAttr("disabled", "disabled")
						$("#read_documents").removeAttr("disabled", "disabled")

						$("#username").removeAttr("readonly")
						$("#password").removeAttr("readonly")
						$("#confirmed_password").removeAttr("readonly")
						$("#email").removeAttr("readonly")
						<?php 
							if (INVITE_ONLY):
						?>
						$("#invite_key").removeAttr("readonly")
						<?php
							endif;
						?>
					}
				})
			}

			$('form input:not([type="submit"])').keypress(function (e)
			{
				if (e.keyCode == 13)
				{
					e.preventDefault()
					return false
				}
			})

			$(function()
			{
				$("#register-form").on("submit", function(e) {
					$("#submit").attr("disabled", "disabled")
					$("#13confirm").attr("disabled", "disabled")
					$("#read_documents").attr("disabled", "disabled")

					$("#username").attr("readonly", "readonly")
					$("#password").attr("readonly", "readonly")
					$("#confirmed_password").attr("readonly", "readonly")
					$("#email").attr("readonly", "readonly")
					<?php 
						if (INVITE_ONLY):
					?>
					$("#invite_key").attr("readonly", "readonly")
					<?php
						endif;
					?>

					e.preventDefault()
					form_register()
				})
			})
		</script>
		
        <div class="container">
			<div class="row flex-center pt-5 mt-3">
				<div class="card" style="width: 40rem">
					<div class="card-header purple accent-3 white-text">
						Sign Up
					</div>

                    <div class="card-body mx-4">
						<form id="register-form">
							<div class="md-form">
								<i class="material-icons prefix grey-text active">person</i>
								<input type="text" id="username" name="username" class="form-control mb-1" required="required">
								<label for="username">Username</label>
								<span class="font-small grey-text mb-1" style="margin-left: 2.5rem">Please choose an appropriate username.</span>
							</div>
							
							<div class="md-form">
								<i class="material-icons prefix grey-text">email</i>
								<input type="email" id="email" name="email" class="form-control mb-1" required="required">
								<label for="email">E-Mail address</label>
								<span class="font-small grey-text mb-1" style="margin-left: 2.5rem">Give us a valid E-Mail address; this will be used for verification.</span>
							</div>
							
							<div class="md-form">
								<i class="material-icons prefix grey-text">vpn_key</i>
								<input type="password" id="password" name="password" class="form-control mb-1" required="required">
								<label for="password">Password</label>
								<span class="font-small grey-text mb-1" style="margin-left: 2.5rem">Passwords are hashed via <a href="https://en.wikipedia.org/wiki/Argon2">Argon2</a>.</span>
							</div>
							
							<div class="md-form mb-0">
								<i class="material-icons prefix grey-text">vpn_key</i>
								<input type="password" id="confirmed_password" name="confirmed_password" class="form-control" required="required">
								<label for="confirmed_password">Confirm password</label>
							</div>

							<p class="font-small blue-text d-flex justify-content-end mb-0"><a href="/forgot-credentials" class="blue-text ml-1">Forgot Username / Password?</a></p>

							<?php
								if (INVITE_ONLY):
							?>

							<div class="md-form mb-4 mt-1">
								<i class="material-icons prefix grey-text">fingerprint</i>
								<input type="text" id="invite_key" name="invite_key" class="form-control mb-1" required="required">
								<label for="invite_key">Invite key</label>
								<span class="font-small grey-text mb-1" style="margin-left: 2.5rem"><?php echo(BASE_NAME); ?> is currently invite only.</span>
							</div>

							<?php
								endif;
							?>

							<div class="pb-3 mb-0" align="center">
								<div class="mb-1">
									<div class="g-recaptcha" data-sitekey="<?php echo(RECAPTCHA_PUBLIC_KEY); ?>"></div>
								</div>
							</div>

							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="13confirm" required="required">
								<label class="form-check-label" for="13confirm">I am 13 years old or older</label>
							</div>

							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="read_documents" required="required">
								<label class="form-check-label" for="read_documents">I have read and agree to the <a href="/terms-of-service">Terms of Service</a> and the <a href="/privacy-policy">Privacy Policy</a></label>
							</div>
							
							<br>
						
							<div class="text-center mb-2 mt-0 pt-0">
								<button type="submit" id="submit" class="btn purple-gradient accent-1 btn-block btn-rounded z-depth-1a waves-effect waves-light" name="submit">Sign Up</button>
							</div>
						</form>
                    </div>

					<div class="modal-footer mx-5 pt-3 mb-1">
						<p class="font-small grey-text d-flex justify-content-end">Already a member? <a href="/login" class="blue-text ml-1">Login</a></p>
					</div>
				</div>
			</div>
		</div>

		<?php
			build_footer();
		?>
	</body>
</html>
