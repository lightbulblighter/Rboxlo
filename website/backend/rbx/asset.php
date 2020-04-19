<?php
    // ************************************************************
    // THIS SCRIPT WILL GET INCLUDED BY FRON FACING ASSET ENDPOINTS
    // DO NOT PUT IT ANYWHERE ELSE, OR STUFF WILL BREAK
    // THANK YOU
    // ************************************************************

    // ***********************
    // TODO: ASSET PERMISSIONS
    // ***********************

    require_once("/var/www/backend/includes.php");

    if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0 || !is_int($_GET["id"])) // I use strlen instead of empty because empty returns "false" if it's "falsey", e.g asset id "0"
    {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
     
    $id = $_GET["id"];

    if (file_exists(file_build_path("assets", $id)))
    {
        readfile(file_build_path("assets", $id));
    }
    else if (file_exists(file_build_path("assets", $id . ".script")))
    {
        header("Content-Type: text/plain");
        
        echo(file_get_contents(file_build_path("assets", $id . ".script")));
    }
    else if (file_exists(BASE_PATH ."/assets/". $id . ".corescript"))
    {
        header("Content-Type: text/plain");
        
        $script = file_get_contents(file_build_path("assets", $id . ".corescript"));
        $signature = get_signature($script);

        echo("--rbxsig%". $signature ."%\n");
        echo("--rbxid%". $id ."%\n")
        echo(file_get_contents(file_build_path("assets", $id . ".corescript")));
    }
    else
    {
        header("HTTP/1.0 404 Not Found");
    }

    exit();
?>