<?php
    require_once("/var/www/backend/includes.php");

    if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0 || !is_int($_GET["id"])) // I use strlen instead of empty because empty returns "false" if it's "falsey", e.g asset id "0"
    {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    // ***********************
    // TODO: ASSET PERMISSIONS
    // ***********************
     
    $id = $_GET["id"];

    if (file_exists(BASE_PATH ."/assets/". $id))
    {
        readfile(BASE_PATH ."/assets/". $id);
    }
    else if (file_exists(BASE_PATH ."/assets/". $id . ".script"))
    {
        header("Content-Type: text/plain");
        
        echo(file_get_contents(BASE_PATH ."/assets/". $id));
    }
    else if (file_exists(BASE_PATH ."/assets/". $id . ".corescript"))
    {
        header("Content-Type: text/plain");
        
        $script = file_get_contents(BASE_PATH ."/assets/". $id);
        $signature = get_signature($script);

        echo("--rbxsig%". $signature ."%\n");
        echo("--rbxid%". $id ."%\n")
        echo(file_get_contents(BASE_PATH ."/assets/". $id));
    }
    else
    {
        header("HTTP/1.0 404 Not Found");
    }

    exit();
?>
    