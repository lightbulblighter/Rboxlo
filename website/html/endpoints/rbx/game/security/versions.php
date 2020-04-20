<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");

    header("Content-Type: text/plain");
    
    if (!isset($_GET["apiKey"]) || empty($_GET["apiKey"]))
    {
        exit(json_encode([
            "Message" => "No HTTP resource was found that matches the request URI 'https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ."'."
        ]));
    }

    $key = $_GET["apiKey"];

    $statement = $GLOBALS["sql"]->prepare("SELECT `version` FROM `api_keys` WHERE `key` = ? AND `usage` = ?");
    $statement->execute([$key, "get_security_versions"]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$result)
    {
        exit(json_encode([
            "Message" => "No HTTP resource was found that matches the request URI 'https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ."'."
        ]));
    }

    $statement = $GLOBALS["sql"]->prepare("SELECT `details` FROM `client_versions` WHERE `version` = ?");
    $statement->execute([$result["version"]]);
    
    $data = [];

    foreach ($statement as $result)
    {
        $data[] = $result["details"];
    }

    exit(json_encode([
        "data" => $data
    ]));
?>