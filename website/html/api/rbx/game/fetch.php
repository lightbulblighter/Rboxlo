<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

    if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0 || !is_int((int)$_GET["id"]))
    {
        exit(json_encode([
            "success" => false,
            "message" => "Invalid place ID."
        ]));
    }

    $statement = "SELECT * FROM `places` WHERE `id` = ? AND `deleted` = 0";
    $statement->execute([$_GET["id"]]);
    $place = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$place)
    {
        exit(json_encode([
            "success" => false,
            "message" => "That place does not exist."
        ]));
    }

    for ($i = 0; $i < 5; $i++)
    {
        $statement = "SELECT * FROM `jobs` WHERE `place` = ? AND `deleted` = 0";
        $statement->execute([$_GET["id"]]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        
    }

    exit(json_encode([
        "success" => true,
        "message" => "This works!",
        "payload" => "caca://a"
    ]))
?>