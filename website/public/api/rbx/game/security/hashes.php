<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx.php");
    header("Content-Type: application/json");
    
    $key = get_api_key($_GET["apiKey"]);

    if (!$key || $key["usage"] !== "get_security_information")
    {
        exit(json_encode([
            "Message" => "No HTTP resource was found that matches the request URI " . get_server_host() . "/" . $_SERVER["REQUEST_URI"] ."'."
        ]));
    }

    open_database_connection($sql);

    $statement = $sql->prepare("SELECT `player_hash` FROM `client_versions` WHERE `year` = ? AND 'latest' = 1");
    $statement->execute([$key["version"]]);

    $data = [];

    foreach ($statement as $result)
    {
        $data[] = $result["player_hash"];
    }

    close_database_connection($sql, $statement);

    exit(json_encode([
        "data" => $data
    ]));
?>