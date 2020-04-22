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
        "ClientPort" => 0,
        "MachineAddress" => $token["ip"],
        "ServerPort" => $token["port"],
        "PingUrl" => "https://www.". BASE_URL ."/endpoints/rbx/game/client/ping",
        "PingInterval" => 120,
        "UserName" => $user["username"],
        "SeleniumTestMode" => false,
        "UserId" => $user["id"],
        "SuperSafeChat" => (bool)$user["ssc"],
        "CharacterAppearance" => "https://www.". BASE_URL ."/endpoints/rbx/avatar/fetch?userId=". $user["id"] ."&pagal=". time(),
        "ClientTicket" => "",
        "GameId" => $game["full_id"],
        "PlaceId" => $place["id"],
        "MeasurementUrl" => "",
        "WaitingForCharacterGuid" => sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)),
        "BaseUrl" => "https://www.". BASE_URL ."/",
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
        "IsRobloxPlace" => true,
        "GenerateTeleportJoin" => false,
        "IsUnknownOrUnder13" => false,
        "SessionId" => "",
        "DataCenterId" => 0,
        "UniverseId" => 0,
        "BrowserTrackerId" => 0,
        "UsePortraitMode" => false,
        "FollowUserId" => 0,
        "characterAppearanceId" => $user["id"]
    ], JSON_UNESCAPED_SLASHES);

    $joinscript = '{"ClientPort":0,"MachineAddress":"127.0.0.1","ServerPort":53640,"PingUrl":"","PingInterval":120,"UserName":"lightbulblighter","SeleniumTestMode":false,"UserId":0,"SuperSafeChat":false,"CharacterAppearance":"https://api.jkora17.pw/v1.1/avatar-fetch/?placeId=0&userId=0","ClientTicket":"","GameId":"00000000-0000-0000-0000-000000000000","PlaceId":0,"MeasurementUrl":"","WaitingForCharacterGuid":"08d7557b-2843-4a03-82f7-2723e47e2371","BaseUrl":"http://assetgame.jkora17.pw/","ChatStyle":"Classic","VendorId":0,"ScreenShotInfo":"","VideoInfo":"<?xml version=\"1.0\"?><entry xmlns=\"http://www.w3.org/2005/Atom\" xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:yt=\"http://gdata.youtube.com/schemas/2007\"><media:group><media:title type=\"plain\"><![CDATA[ROBLOX Place]]></media:title><media:description type=\"plain\"><![CDATA[ For more games visit http://www.jkora17.pw]]></media:description><media:category scheme=\"http://gdata.youtube.com/schemas/2007/categories.cat\">Games</media:category><media:keywords>ROBLOX, video, free game, online virtual world</media:keywords></media:group></entry>","CreatorId":0,"CreatorTypeEnum":"User","MembershipType":"None","AccountAge":0,"CookieStoreFirstTimePlayKey":"rbx_evt_ftp","CookieStoreFiveMinutePlayKey":"rbx_evt_fmp","CookieStoreEnabled":true,"IsRobloxPlace":false,"GenerateTeleportJoin":false,"IsUnknownOrUnder13":true,"SessionId":"89e81fb5-d1c8-48a9-a127-5d0d6bddaaac|00000000-0000-0000-0000-000000000000|0|207.241.231.247|5|2016-11-27T15:55:58.4473206Z|0|null|null|37.7811|-122.4625|1","DataCenterId":0,"UniverseId":0,"BrowserTrackerId":0,"UsePortraitMode":false,"FollowUserId":0,"characterAppearanceId":0}';
    // Sign joinscript
    $signature = get_signature("\n" . $joinscript);

    // Construct full result
    $result = "--rbxsig". $signature . "\n" . $joinscript;
    
    // Return
    exit($result);
?>