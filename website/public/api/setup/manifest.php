<?php
    header("Content-Type: application/json");

    // validate query
    if (!ctype_alnum($_GET["version"]) || !ctype_alnum($_GET["hash"]) || !isset($_GET["hash"]) || !isset($_GET["version"]) || empty($_GET["hash"]) || empty($_GET["version"]))
    {
        exit(json_encode([
            "success" => false
        ]));
    }

    $version = filter_var($_GET["version"], FILTER_SANITIZE_STRING);
    $hash = filter_var($_GET["hash"], FILTER_SANITIZE_STRING);

    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/../data/setup/$version") || !file_exists($_SERVER["DOCUMENT_ROOT"] . "/../data/setup/$version/$hash"))
    {
        exit(json_encode([
            "success" => false
        ]));
    }

    $manifest = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../data/setup/$version/manifest/$hash.json");
    exit($manifest); // it's a json document
?>