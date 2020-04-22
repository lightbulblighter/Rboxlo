<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

    header("Content-Type: text/plain");

    if (!isset($_GET["token"]) || empty($_GET["token"]) || !ctype_alnum($_GET["token"]))
    {
        exit("Invalid token");
    }

    $statement = "SELECT * FROM `game_tokens` WHERE `token` = ?";
    $statement->execute([$_GET["token"]]);
    $token = $statement->fetch($_GET["token"]);
    if (!$token)
    {
        exit("No token");
    }

    $statement = "SELECT * FROM `users` WHERE `id` = ?";
    $statement->execute([$token["user_id"]]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$user)
    {
        exit("No user");
    }

    $statement = "SELECT * FROM `games` WHERE `id` = ?";
    $statement->execute([$token["game_id"]]);
    $game = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$game)
    {
        exit("No game");
    }

    $statement = "SELECT * FROM `places` WHERE `id` = ?";
    $statement->execute([$token["place_id"]]);
    $place = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$place)
    {
        exit("No place");
    }

    // Construct joinscript
    $joinscript = json_encode([
        ["ClientPort"] => rand(0, 65536),
        ["MachineAddress"] => $token["ip"],
        ["ServerPort"] => $token["port"],
        ["PingUrl"] => "https://www.". BASE_URL ."/endpoints/rbx/game/client/ping",
        ["PingInterval"] => 120,
        ["SeleniumTestMode"] => false,
        ["UserId"] => $user["id"],
        ["RobloxLocale"] => "en_us",
        ["GameLocale"] => "en_us",
        ["SuperSafeChat"] => (bool)$user["ssc"],
        ["CharacterAppearance"] => "https://www.". BASE_URL ."/endpoints/rbx/avatar/fetch?userId=". $user["id"] ."&pagal=". time(),
        ["ClientTicket"] => "",
        ["NewClientTicket"] => "",
        ["GameId"] => $game["full_id"],
        ["PlaceId"] => $place["id"],
        ["MeasurementUrl"] => "",
        ["BaseUrl"] => "https://www.". BASE_URL ."/",
        ["ChatStyle"] => $place["chat_style"]
    ]);

    // Sign joinscript
    $signature = get_signature($joinscript);

    // Construct full result
    $result = "--rbxsig". $signature . "\n". $joinscript;
    
    // Return
    exit($result);
?>