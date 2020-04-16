<?php
    include_once("/var/www/backend/configuration.php"); // change this for path

    include_once(BASE_PATH ."/backend/functions.php");
    include_once(BASE_PATH ."/backend/main.php");
    include_once(BASE_PATH ."/backend/database.php");

    include_once(BASE_PATH ."/backend/html.php");
	
	# strstr() removes everything after the ?, it returns false if it didn't remove anything so we also need to run endsWith without strstr()
	if (endsWith(strstr($_SERVER['REQUEST_URI'], '?', true), '.php') || endsWith($_SERVER['REQUEST_URI'], '.php'))
	{
		header('HTTP/1.1 404 Not Found');
		require_once(BASE_PATH ."/html/error/404.php");
		exit();
	}
?>