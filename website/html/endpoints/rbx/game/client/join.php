<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

    header("Content-Type: text/plain");

    if (!isset($_GET["token"]) || empty($_GET["token"]) || !ctype_alnum($_GET["token"]))
    {
        exit("Invalid token");
    }

    $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `game_tokens` WHERE `token` = ?");
    $statement->execute([$_GET["token"]]);
    $token = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$token)
    {
        exit("No token");
    }

    // See if token expired
    $elapsed = time() - $token["generated"];
    if ($elapsed >= 300) // 300 seconds = 5 minutes, wayy too long
    {
        // Kill token
        $statement = $GLOBALS["sql"]->prepare("DELETE FROM `game_tokens` WHERE `token` = ?");
        $statement->execute([$_GET["token"]]);

        // Exit
        exit("Token expired");
    }

    $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $statement->execute([$token["user_id"]]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$user)
    {
        exit("No user");
    }

    $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `games` WHERE `id` = ?");
    $statement->execute([$token["game_id"]]);
    $game = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$game)
    {
        exit("No game");
    }

    $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `places` WHERE `id` = ?");
    $statement->execute([$token["place_id"]]);
    $place = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$place)
    {
        exit("No place");
    }

    // Kill token
    //$statement = $GLOBALS["sql"]->prepare("DELETE FROM `game_tokens` WHERE `token` = ?");
    //$statement->execute([$_GET["token"]]);

    // Construct joinscript (this is a mess)
    $joinscript = json_encode([
        "ClientPort" => rand(0, 65536),
        "MachineAddress" => $token["ip"],
        "ServerPort" => $token["port"],
        "PingUrl" => "https://www.". BASE_URL ."/endpoints/rbx/game/client/ping",
        "PingInterval" => 120,
        "SeleniumTestMode" => false,
        "UserId" => $user["id"],
        "RobloxLocale" => "en_us",
        "GameLocale" => "en_us",
        "SuperSafeChat" => (bool)$user["ssc"],
        "CharacterAppearance" => "https://www.". BASE_URL ."/endpoints/rbx/avatar/fetch?userId=". $user["id"] ."&pagal=". time(),
        "ClientTicket" => "",
        "GameId" => $game["full_id"],
        "PlaceId" => $place["id"],
        "MeasurementUrl" => "",
        "BaseUrl" => "https://www.". BASE_URL ."/",
        "ChatStyle" => $place["chat_style"],
        "WaitingForCharacterGuid" => sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)),
        "VendorId" => "0",
        "ScreenShotInfo" => "",
        "VideoInfo" => "",
        "CreatorId" => $place["creator"],
        "CreatorTypeEnum" => "User",
        "MembershipType" => "None",
        "AccountAge" => round((time() - $user["joindate"])/86400),
        "CookieStoreFirstTimePlayKey" => "rbx_evt_ftp",
        "CookieStoreFiveMinutePlayKey" => "rbx_evt_fmp",
        "CookieStoreEnabled" => true,
        "IsRobloxPlace" => false,
        "GenerateTeleportJoin" => false,
        "IsUnknownOrUnder13" => false,
        "SessionId" => "",
        "DataCenterId" => 0,
        "UniverseId" => $game["id"],
        "BrowserTrackerId" => 0,
        "UsePortraitMode" => false,
        "FollowUserId" => 0,
        "characterAppearanceId" => 0
    ]);

    // Sign joinscript
    $signature = get_signature($joinscript);

    // Construct full result
    $result = "--rbxsig". $signature; //. "\n". $joinscript;
    
    // Return
    exit($result);
?>