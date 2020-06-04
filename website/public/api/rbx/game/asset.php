<?php
    // ***********************
    // TODO: ASSET PERMISSIONS
    // TODO: Register /data/thumbnails/ as assets, somehow
    // ***********************

    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx.php");

    if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0 || !is_int((int)$_GET["id"])) // I use strlen instead of empty because empty returns "false" if it's "falsey", e.g asset id "0"
    {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
     
    $id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);
    $version = isset($_GET["version"]) ? filter_var($_GET["version"], FILTER_SANITIZE_NUMBER_INT) : "latest";

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/../data/assets/" . $id . "/" . $version))
    {
        readfile($_SERVER["DOCUMENT_ROOT"] . "/../data/assets/" . $id . "/" . $version);
    }
    else if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/../data/assets/" . $id . ".script/" . $version))
    {
        header("Content-Type: text/plain");
        
        echo(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../data/assets/" . $id . ".script/". $version));
    }
    else if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/../data/assets/" . $id . ".corescript/". $version))
    {
        header("Content-Type: text/plain");
        
        $script = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../data/assets/" . $id . ".corescript/". $version);
        $signature = get_signature($script);

        echo("--rbxsig%". $signature ."%\n");
        echo("--rbxid%". $id ."%\n");
        echo(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../data/assets/" . $id . ".corescript/". $version));
    }
    else
    {
        header("HTTP/1.0 404 Not Found");
    }

    exit();
?>