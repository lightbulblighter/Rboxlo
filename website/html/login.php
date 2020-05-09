<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");

	if (isset($_SESSION["user"]))
	{
		redirect("/my/dashboard");
	}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Login");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
		?>

		<script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>

		<script type="text/javascript">
			function form_login()
			{
				var information = {
					username: $("#username").val(),
					password: $("#password").val(),
					csrf: "<?php echo($_SESSION["csrf"]); ?>"
				}

				endpoint("/authentication/login", "POST", information, (response) =>
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
						}, 2000)
					}
					else
					{
						$("#submit").removeAttr("disabled", "disabled")

						$("#username").removeAttr("readonly")
						$("#password").removeAttr("readonly")
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
				$("#login-form").on("submit", function(e) {
					$("#submit").attr("disabled", "disabled")
					
					$("#username").attr("readonly", "readonly")
					$("#password").attr("readonly", "readonly")

					e.preventDefault()
					form_login()
				})
			})
		</script>

        <div class="container">
			<div class="row flex-center">
				<div class="card" style="width: 40rem">
					<div class="card-header purple accent-3 white-text">
						Login
					</div>

                    <div class="card-body mx-4">
						<form id="login-form">
							<div class="md-form">
								<i class="material-icons prefix grey-text active">person</i>
								<input type="text" id="username" name="username" class="form-control mb-1" required="required">
								<label for="username">Username</label>
							</div>
							
							<div class="md-form">
								<i class="material-icons prefix grey-text">vpn_key</i>
								<input type="password" id="password" name="password" class="form-control mb-1" required="required">
								<label for="password">Password</label>
							</div>

							<p class="font-small blue-text d-flex justify-content-end mb-0"><a href="/forgot-credentials" class="blue-text ml-1">Forgot Username / Password?</a></p>
							
							<br>
						
							<div class="text-center mb-3 mt-0 pt-0">
								<button type="submit" id="submit" class="btn purple-gradient accent-1 btn-block btn-rounded z-depth-1a waves-effect waves-light" name="submit">Login</button>
							</div>
						</form>
                    </div>

					<div class="modal-footer mx-5 pt-3 mb-1">
						<p class="font-small grey-text d-flex justify-content-end">Don't have an account? <a href="/register" class="blue-text ml-1">Sign Up</a></p>
					</div>
				</div>
			</div>
		</div>

		<?php
			build_footer();
		?>
	</body>
</html>
