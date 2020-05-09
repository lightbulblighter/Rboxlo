<?php
    try
    {
        // Why can't I just pass variables between php scripts? Why do I need to declare a global variable?
        // PHP pretends to be an OOP language, but when you actually *try* OOP you see how not OOP-ish it is.
        global $sql;

		$sql = new PDO("mysql:host=". ENVIRONMENT["SQL"]["HOST"] .";port=". ENVIRONMENT["SQL"]["PORT"] .";dbname=". ENVIRONMENT["SQL"]["DATABASE"], ENVIRONMENT["SQL"]["USERNAME"], ENVIRONMENT["SQL"]["PASSWORD"]);
		$sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$sql->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
    catch (PDOException $error)
    {
	    error_log($error);
        exit(ENVIRONMENT["PROJECT"]["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>Error code: ". $error->errorCode());
    }
    catch (exception $e)
    {
        error_log($error);
        exit(ENVIRONMENT["PROJECT"]["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>Error code: UNKNOWN_ERR");
    }
?>