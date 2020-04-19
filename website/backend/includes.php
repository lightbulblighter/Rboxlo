<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../constant/configuration.php");
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/main.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/functions.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/database.php");
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/html.php");
	
	// strstr() removes everything after the ?, it returns false if it didn"t remove anything so we also need to run endsWith without strstr()
	if (ends_with(strstr($_SERVER["REQUEST_URI"], "?", true), ".php") || ends_with($_SERVER["REQUEST_URI"], ".php"))
	{
		header("HTTP/1.1 404 Not Found");
		
		require_once($_SERVER["DOCUMENT_ROOT"] . "/../html/error/404.php");
		exit();
	}
?>