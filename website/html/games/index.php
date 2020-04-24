<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");

	if (!isset($_SESSION["user"]))
	{
		redirect("/login");
	}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<?php
			build_header("Games");
		?>
	</head>
	<body>
		<?php
			build_js();
			build_navigation_bar();
		?>

        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script type="text/javascript" src="/core/html/js/api.js" async defer></script>

        <script type="text/javascript">
            // Me when I skid off of Ourblox code: (this project)
            // Credits to 1002 for this

            function start_game(id)
            {
                Swal.fire({
                    title: "Requesting game...",
                    allowOutsideClick: () => !Swal.isLoading(),
                    onBeforeOpen: () =>
                    {
                        Swal.showLoading()
                        return fetch(`/endpoints/rbx/game/fetch`)
                            .then(response =>
                            {
                                if (!response.success)
                                {
                                    throw new Error(response.status)
                                }
                            })
                            .catch(error =>
                            {
                                Swal.fire({
                                    title: "An error occurred.",
                                    text: error,
                                    type: "error"
                                })
                            })
                            .then((result) =>
                            {
                                if (result.payload)
                                {
                                    Swal.fire({
                                        title: "Opening game...",
                                        text: "This shouldn't take long.",
                                        type: "success"
                                    })

                                    window.location.href = result.payload
                                }
                                else
                                {
                                    Swal.fire({
                                        title: "An error occurred.",
                                        text: result.message,
                                        type: "error"
                                    })
                                }
                            })
                    }
                })
            }
        </script>

        <div class="container">
            <p class="h1" align="center">Very Complete Games Page</p>
            <p class="h5 font-weight-light" align="center">Click the link to join. You need the game executable in order to play games.</p>
            <br>
            
            <?php
                $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `places`");
                $statement->execute();

                foreach ($statement as $place)
                {
                    $statement = $GLOBALS["sql"]->prepare("SELECT `username` FROM `users` WHERE `id` = ?");
                    $statement->execute([$place["creator"]]);
                    $user = $statement->fetch(PDO::FETCH_ASSOC);

                    echo("<a onclick='start_game(". $place["id"] .")'>". $place["name"] ." by ". $user["username"] ."</a>");
                }
            ?>
		</div>

        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><!-- me when I <br> -->

		<?php
			build_footer();
		?>
	</body>
</html>
