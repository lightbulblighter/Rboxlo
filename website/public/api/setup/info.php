<?php
    header("Content-Type: application/json");

    // I hate databases.
    $files = array_merge(glob($_SERVER["DOCUMENT_ROOT"] . "/../data/setup/launcher/*"));
    $files = array_combine($files, array_map("filemtime", $files));
    arsort($files);

    $latest_file = basename(key($files));
    
    exit(json_encode([
        "success" => true,
        "launcher" => $latest_file
    ]));
?>