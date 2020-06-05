<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");
    open_database_connection($sql);
    header("Content-Type: application/json");

    // The job of the matchmaker is to validate a few key components, and then pass this on to the matchmaker
    // which runs on port 3000. I could not find a non-nonsensical method of asynchronous matchmaking in PHP,
    // so I split the matchmaker into two parts: This part (PHP) and the actual matchmaker (in node.js).
    // They might be merged in the future; but for now, this is the most viable solution.

    // I would also like to add that this matchmaker took 100% of my brains capacity to make.

    function match($place_id, $game_id, $job)
    {
        $token = bin2hex(random_bytes(64));
        $statement = $sql->prepare("INSERT INTO game_tokens(`token`, `generated`, `user_id`, `game_id`, `place_id`, `ip`, `port`) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $statement->execute([$token, time(), $_SESSION["user"]["id"], $game_id, $place_id, $job["ip"], $job["port"]]);
        
        $payload = "2017+client+". $token . "+". time(); 
        $payload = "rboxlo://" . base64_encode($payload); // Base64 encoding is just so that data gets transferred properly,
                                                          // not for encryption (or obfuscation.) It isn't security.

        exit(json_encode([
            "success" => true,
            "payload" => $payload
        ]));
    }

    if (!isset($_SESSION["user"]))
    {
        exit(json_encode([
            "success" => false,
            "message" => "Not logged in."
        ]));
    }

    if (!$_SESSION["user"]["email_verified"])
    {
        exit(jsonencode([
            "success" => false,
            "message" => "You need to verify your E-Mail address before you can play games."
        ]));
    }

    if (!is_int($_GET["id"]))
    {
        exit(json_encode([
            "success" => false,
            "message" => "Invalid game ID."
        ]));
    }

    $pass = false; // Whether we should pass it to the matchmaker or not.
                   // If this is set to true, it will be passed and a result
                   // will be returned by the matchmaker.

    $game_id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);

    $statement = $sql->prepare("SELECT * FROM `games` WHERE `id` = ? AND `deleted` = 0");
    $statement->execute([$game_id]);
    $game = ["data" => $statement->fetch(PDO::FETCH_ASSOC), "rows" => $statement->fetchColumn()];
    
    if ($game["rows"] <= 0)
    {
        exit(json_encode([
            "success" => false,
            "message" => "Game does not exist."
        ]));
    }

    $statement = $sql->prepare("SELECT * FROM `places` WHERE `id` = ? AND `deleted` = 0");
    $statement->execute([$game["data"]["place_id"]]);
    $place = ["data" => $statement->fetch(PDO::FETCH_ASSOC), "rows" => $statement->fetchColumn()];

    if ($place["rows"] <= 0)
    {
        exit(json_encode([
            "success" => false,
            "message" => "Place does not exist. This should never happen. If you received this error, please contact the"
        ]));
    }

    $statement = $sql->prepare("SELECT * FROM `jobs` WHERE `game_id` = ? AND `deleted` = 0");
    $statement->execute([$game_id]);
    $job = ["data" => $statement->fetch(PDO::FETCH_ASSOC), "rows" => $statement->fetchColumn()];
    $jobID = create_job_id();

    if ($job["rows"] <= 0 && $game["data"]["last_start"] >= time() - 5000)
    {
        $pass = true;
    }
    else if ($job["rows"] <= 0)
    {
        // Create a job
        $gameserver_ip = array_rand(ENVIRONMENT["GAMESERVER"]["IPS"]);
        $gameserver_port = mt_rand(0, 65535);
        $statement = $sql->prepare("INSERT INTO jobs(`name`, `ip`, `port`, `game_id`, `started`, `expiration`, `version`) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $statement->execute([$jobID, $gameserver_ip, $gameserver_port, $game_id, time(), time() + 604800, "2017"]);

        open_job($gameserver_ip, $gameserver_port, $jobID, [
            "id" => $jobID,
            "expiration" => 604800,
            "cores" => 1,
            "category" => $_GET["id"],
            "script" => "loadstring(game:HttpGet('". get_server_host() . "/api/rbx/game/server/gameserver?data=". $game_id . ";0;". $jobID . ";". get_server_host() . "', true))()"
        ]);
        
        // Pass
        $pass = true;
    }
    else
    {
        match($place["data"]["id"], $game_id, $job);
    }

    close_database_connection($sql, $statement); // We're done with database stuff here, lets close it early
                                                 // so that the matchmaker doesn't potentially error.

    if ($pass) // Pass to the matchmaker
    {   
        $options = [
            "http" => [
                "method"  => "POST",
                "content" => json_encode(["id" => $_GET["id"]]),
                "header"  => "Content-Type: application/json\r\n"
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents(ENVIRONMENT["GAMESERVER"]["MATCHMAKER"] . "/arrange", false, $context);

        // Parse matchmaker result
        try
        {
            $result = json_decode($result, true);
        }
        catch (Exception $e)
        {
            exit(json_encode([
                "success" => false,
                "message" => "Matchmaker returned unknown error."
            ]));
        }

        if (!$result["success"])
        {
            exit(json_encode([
                "success" => false,
                "message" => $result["message"]
            ]));
        }

        // Open database connection, again (but we don't occupy it while its being matched)
        open_database_connection($sql);

        $statement = $sql->prepare("SELECT * FROM `jobs` WHERE `name` = ?");
        $statement->execute([$result["name"]]);
        $job = $statement->fetch(PDO::FETCH_ASSOC);

        match($place["data"]["id"], $game_id, $job);

        // Close again. lol
        close_database_connection($sql, $statement);
    }  

    // Just in case!
    close_database_connection($sql, $statement);
?>