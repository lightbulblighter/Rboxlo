<?php
    header("Content-Type: application/json");

    // validate query
    if (!ctype_alnum($_GET["version"]) || !isset($_GET["version"]) || empty($_GET["version"]))
    {
        exit(json_encode([
            "success" => false
        ]));
    }

    $version = filter_var($_GET["version"], FILTER_SANITIZE_STRING);
    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/../application/data/setup/$version"))
    {
        exit(json_encode([
            "success" => false
        ]));
    }

    // it exists, lets get it's latest file
    $files = array_merge(glob($_SERVER["DOCUMENT_ROOT"] . "/../data/setup/$version/*"));
    $files = array_combine($files, array_map("filemtime", $files));
    arsort($files);

    $latest_file = basename(key($files));
    
    exit(json_encode([
        "success" => true,
        "file" => $latest_file
    ]));
?>