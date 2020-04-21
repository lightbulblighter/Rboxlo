<?php
    // ***********************
    // TODO: ASSET PERMISSIONS
    // ***********************

    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

    if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0 || !is_int($_GET["id"])) // I use strlen instead of empty because empty returns "false" if it's "falsey", e.g asset id "0"
    {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
     
    $id = $_GET["id"];

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/../static/assets/" . $id))
    {
        readfile($_SERVER["DOCUMENT_ROOT"] . "/../static/assets/" . $id);
    }
    else if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/../static/assets/" . $id . ".script"))
    {
        header("Content-Type: text/plain");
        
        echo(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../static/assets/" . $id . ".script"));
    }
    else if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/../static/assets/" . $id . ".corescript"))
    {
        header("Content-Type: text/plain");
        
        $script = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../static/assets/" . $id . ".corescript");
        $signature = get_signature($script);

        echo("--rbxsig%". $signature ."%\n");
        echo("--rbxid%". $id ."%\n");
        echo(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../static/assets/" . $id . ".corescript"));
    }
    else
    {
		error_log("Asset not found: $id")
        header("HTTP/1.0 404 Not Found");
    }

    exit();
?>