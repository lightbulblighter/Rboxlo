<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/functions.php");
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/main.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/database.php");
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/html.php");
	
	// strstr() removes everything after the ?, it returns false if it didn"t remove anything so we also need to run endsWith without strstr()
	if (ends_with(strstr($_SERVER["REQUEST_URI"], "?", true), ".php") || ends_with($_SERVER["REQUEST_URI"], ".php"))
	{
		header("HTTP/1.1 404 Not Found");
		
		require_once($_SERVER["DOCUMENT_ROOT"] . "/../public/error/404.php");
		exit();
	}
?>