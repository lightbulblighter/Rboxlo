<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

    header("Content-Type: text/plain");

    if ($_SERVER["HTTP_USER_AGENT"] !== "Roblox/WinInet")
    {
        exit("Not being called from Roblox client");
    }

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
    $statement = $GLOBALS["sql"]->prepare("DELETE FROM `game_tokens` WHERE `token` = ?");
    $statement->execute([$_GET["token"]]);

    // Get exact time
    $exact_time = date("Y-d-m") . "T" . date("H:i:s.") . substr(milliseconds(), 0, 7) . "Z";

    // Create session guid
    $session_id = get_random_guid();

    // Construct joinscript
    $joinscript = [
        "ClientPort" => 0,
        "MachineAddress" => $token["ip"] ?? "127.0.0.1",
        "ServerPort" => $token["port"] ?? 53640,
        "PingUrl" => "http://". BASE_URL ."/endpoints/rbx/game/client/ping?id=". $user["id"] ."&place=". $place["id"],
        "PingInterval" => 120,
        "UserName" => $user["username"],
        "SeleniumTestMode" => false,
        "UserId" => $user["id"],
        "SuperSafeChat" => false,
        "CharacterAppearance" => "http://api.". BASE_URL ."/v1.1/avatar-fetch/?placeId=". $place["id"] ."&userId=". $user["id"],
        "ClientTicket" => "",
        "GameId" => $game["full_id"],
        "PlaceId" => $place["id"],
        "MeasurementUrl" => "", // No telemetry here :)
        "WaitingForCharacterGuid" => get_random_guid(),
        "BaseUrl" => "http://www.". BASE_URL ."/",
        "ChatStyle" => $place["chat_style"],
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
        "IsUnknownOrUnder13" => false, // You have to be 13+ to sign up...
        "SessionId" => $session_id . "|" . $game["full_id"] . "|" . $place["id"] . "|". get_user_ip() . "|0|". $exact_time . "|0|null|null|0|0|0",
        "DataCenterId" => 0,
        "UniverseId" => 0,
        "BrowserTrackerId" => 0,
        "UsePortraitMode" => false,
        "FollowUserId" => 0,
        "characterAppearanceId" => $user["id"]
    ];

    // Encode it!
    $data = json_encode($joinscript, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

    // Sign joinscript
    $signature = get_signature("\r\n" . $data);

    // exit
    exit("--rbxsig%". $signature . "%\r\n" . $data);
?>