<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
	http_response_code(400);
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("400");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
		?>

        <div class="container">
			<div class="row flex-center">
                <div class="card" style="width: 40rem">
					<div class="card-header purple accent-3 white-text">
                        Error
                    </div>

                    <div class="card-body mx-4">
                        <div class="text-center">
                            <h1>400</h1>
                            Bad Request - we could not process the request you sent. Please try again later.
                        </div>
                    </div>

					<div class="modal-footer mx-5 pt-3 mb-1">
						<button class="btn purple-gradient accent-1 btn-block btn-rounded z-depth-1a waves-effect waves-light" onclick="window.location.href = '/'">Go home</button>
					</div>
				</div>
            </div>
        </div>
        
        <br><br><br><br><br><br><br>

		<?php
			build_footer();
		?>
	</body>
</html>