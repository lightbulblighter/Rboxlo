<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");

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
			build_navigation_bar();
		?>

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
								<input type="text" id="username" name="username" class="form-control mb-1 login-input" required="required">
								<label for="username">Username</label>
							</div>
							
							<div class="md-form">
								<i class="material-icons prefix grey-text">vpn_key</i>
								<input type="password" id="password" name="password" class="form-control mb-1 login-input" required="required">
								<label for="password">Password</label>
							</div>

							<p class="font-small blue-text d-flex justify-content-end mb-0"><a href="/forgot-credentials" class="blue-text ml-1">Forgot Username / Password?</a></p>
							
							<br>
						
							<div class="text-center mb-3 mt-0 pt-0">
								<button type="submit" id="submit" class="btn purple-gradient accent-1 btn-block btn-rounded z-depth-1a waves-effect waves-light login-input" name="submit">Login</button>
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

		<script type="text/javascript" src="<?= get_server_host() ?>/html/js/login.min.js" async defer></script>
	</body>
</html>
