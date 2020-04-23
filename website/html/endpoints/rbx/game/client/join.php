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

    // Get exact ISO time (THIS CAN BE DONE BETTER!!!)
    function milliseconds()
    {
        $micro = explode(" ", microtime());
        return ((int)$micro[1]) * 1000 + ((int)round($micro[0] * 1000));
    }

    $exact_time = date("Y-d-m") . "T" . date("H:i:s.") . substr(milliseconds(), 0, 7) . "Z";

    // Construct joinscript (this is a mess)
    $joinscript = [
        "ClientPort" => 0,
        "MachineAddress" => $token["ip"] ?? "127.0.0.1",
        "ServerPort" => $token["port"] ?? 53640,
        "PingUrl" => "",
        "PingInterval" => 120,
        "UserName" => $user["username"],
        "SeleniumTestMode" => false,
        "UserId" => $user["id"],
        "SuperSafeChat" => false,
        "CharacterAppearance" => "http://api.". BASE_URL ."/v1.1/avatar-fetch/?placeId=". $place["id"] ."&userId=". $user["id"],
        "ClientTicket" => "",
        "NewClientTicket" => "",
        "GameId" => "00000000-0000-0000-0000-000000000000",
        "PlaceId" => $place["id"],
        "MeasurementUrl" => "",
        "WaitingForCharacterGuid" => "00000000-0000-0000-0000-000000000000",
        "BaseUrl" => "http://assetgame.". BASE_URL ."/",
        "ChatStyle" => $place["chat_style"],
        "VendorId" => "0",
        "ScreenShotInfo" => "",
        "VideoInfo" => "",
        "CreatorId" => 0,
        "CreatorTypeEnum" => "User",
        "MembershipType" => "None",
        "AccountAge" => 0,
        "CookieStoreFirstTimePlayKey" => "rbx_evt_ftp",
        "CookieStoreFiveMinutePlayKey" => "rbx_evt_fmp",
        "CookieStoreEnabled" => true,
        "IsRobloxPlace" => false,
        "GenerateTeleportJoin" => false,
        "IsUnknownOrUnder13" => true,
        "SessionId" => "00000000-0000-0000-0000-000000000000|00000000-0000-0000-0000-000000000000|" . $place["id"] . "|". get_user_ip() . "|0|". $exact_time . "|0|null|null|0|0|0",
        "DataCenterId" => 0,
        "AnalyticsSessionId" => "00000000-0000-0000-0000-000000000000", 
        "UniverseId" => 0,
        "BrowserTrackerId" => 0,
        "UsePortraitMode" => false,
        "FollowUserId" => 0,
        "characterAppearanceId" => $user["id"],
        "CountryCode" => "US"
    ];

    // Encode it!
    $data = json_encode($joinscript, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

    // This is dumb
    $data = "
    ". $data;

    // Sign joinscript
    $signature = get_signature($data);

    // exit
    exit("--rbxsig". $signature . $data);
?>