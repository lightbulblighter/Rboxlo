<?php
    function open_sql_connection(&$database)
    {
        try
        {
            $database = new PDO("mysql:host=". ENVIRONMENT["SQL"]["HOST"] .";port=". ENVIRONMENT["SQL"]["PORT"] .";dbname=". ENVIRONMENT["SQL"]["DATABASE"], ENVIRONMENT["SQL"]["USERNAME"], ENVIRONMENT["SQL"]["PASSWORD"]);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (PDOException $error)
        {
            error_log($error);
            exit(ENVIRONMENT["PROJECT"]["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>Error code: ". $error->errorCode());
        }
        catch (exception $error)
        {
            error_log($error);
            exit(ENVIRONMENT["PROJECT"]["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>Error code: UNKNOWN_ERR");
        }
    }
    
    function close_sql_connection(&$database, &$statement = null)
    {
        $database = null;
        
        if ($statement)
        {
            $statement = null;
        }
    }
?>