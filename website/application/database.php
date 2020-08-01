<?php
    function open_database_connection(&$database)
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment/sql.environment.php");
        
        try
        {
            $database = new PDO("mysql:host=". SQL["HOST"] .";port=". SQL["PORT"] .";dbname=". SQL["DATABASE"], SQL["USERNAME"], SQL["PASSWORD"]);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (PDOException $error)
        {
            error_log($error);
            exit(PROJECT["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>Error: ". $error->getMessage());
        }
        catch (Exception $error)
        {
            error_log($error);
            exit(PROJECT["NAME"] . " is currently experiencing technical difficulties. Please try again later.<br>An unknown error occurred.");
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