<?php
    try
    {
        // Why can't I just pass variables between php scripts? Why do I need to declare a global variable?
        // PHP pretends to be an OOP language, but when you actually *try* OOP you see how not OOP-ish it is.
        global $sql;

		$sql = new PDO("mysql:host=". SQL_DB_HOST .";port=". SQL_DB_PORT .";dbname=". SQL_DB_NAME, SQL_DB_USER, SQL_DB_PASS);
		$sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$sql->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
    catch (exception $e)
    {
        exit("Rboxlo is under maintenance."); // "Check back soon" message is ommitted to indicate that it's a db issue
    }
?>