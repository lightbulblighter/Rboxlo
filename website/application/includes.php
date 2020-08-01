<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment/project.environment.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment/google.environment.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment/repository.environment.php");
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/functions.php");
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/main.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/database.php");
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/html.php");
	
	// Disallow access to pages with ".php"
	if (ends_with(substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")), ".php"))
	{
		require_once($_SERVER["DOCUMENT_ROOT"] . "/../public/error/404.php");
		exit();
	}
?>