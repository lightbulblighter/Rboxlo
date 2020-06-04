<?php
    function open_database_connection(&$database)
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/sql.environment.php"); // Only load in sql credentials if attempting to create a connection
        
        try
        {
            $database = new PDO("mysql:host=". SQL["HOST"] .";port=". SQL["PORT"] .";dbname=". SQL["DATABASE"], SQL["USERNAME"], SQL["PASSWORD"]);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (PDOException $error)
        {
            error_log($error);
            exit(ENVIRONMENT["PROJECT"]["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>Error: ". $error->getMessage());
        }
        catch (exception $error)
        {
            error_log($error);
            exit(ENVIRONMENT["PROJECT"]["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>Error: UNKNOWN_ERR");
        }
    }
    
    function close_database_connection(&$database, &$statement = null)
    {
        $database = null;
        
        if ($statement)
        {
            $statement = null;
        }
    }
?>