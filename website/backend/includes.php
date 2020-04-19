<?php
    include_once("/var/www/constant/configuration.php"); // change this for path
	include_once(BASE_PATH ."/backend/functions.php"); // necessary for file_build_path
	
	include_once(file_build_path("backend", "main.php"));
	include_once(file_build_path("backend", "database.php"));
	
	include_once(file_build_path("backend", "html.php"));
	
	// strstr() removes everything after the ?, it returns false if it didn"t remove anything so we also need to run endsWith without strstr()
	if (ends_with(strstr($_SERVER["REQUEST_URI"], "?", true), ".php") || ends_with($_SERVER["REQUEST_URI"], ".php"))
	{
		header("HTTP/1.1 404 Not Found");
		
		require_once(file_build_path("html", "error", "404.php"));
		exit();
	}
?>