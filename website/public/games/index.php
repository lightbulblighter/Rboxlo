<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");

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
			build_navigation_bar();
		?>

        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <script type="text/javascript" src="<?= get_server_host() ?>/html/js/games.min.js"></script>

        <div class="container">
            <p class="h1" align="center">Very Complete Games Page</p>
            <p class="h5 font-weight-light" align="center">Click the link to join. You need the game executable in order to play games.</p>
            <br>
            
            <?php
				open_database_connection($sql);

                $statement = $sql->prepare("SELECT * FROM `places`");
                $statement->execute();

                foreach ($statement as $place)
                {
                    $statement = $sql->prepare("SELECT `username` FROM `users` WHERE `id` = ?");
                    $statement->execute([$place["creator"]]);
                    $user = $statement->fetch(PDO::FETCH_ASSOC);

                    echo("<a onclick='join(". $place["id"] .")'>". $place["name"] ." by ". $user["username"] ."</a>");
				}
				
				close_database_connection($sql, $statement);
            ?>
		</div>

        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><!-- me when I <br> -->

		<?php
			build_footer();
		?>
	</body>
</html>
