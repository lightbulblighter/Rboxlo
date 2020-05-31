<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");
    open_database_connection($sql);

    // The job of the matchmaker is to validate a few key components, and then pass this on to the matchmaker
    // which runs on port 3000. I could not find a non-nonsensical method of asynchronous matchmaking in PHP,
    // so I split the matchmaker into two parts: This part (PHP) and the actual matchmaker (in node.js).
    // They might be merged in the future; but for now, this is the most viable solution.

    // I would also like to add that this matchmaker took 100% of my brains capacity to make.

    if (!isset($_SESSION["user"]))
    {
        exit(json_encode([
            "success" => false,
            "message" => "Not logged in."
        ]));
    }

    if (!ctype_alnum($_GET["id"]))
    {
        exit(json_encode([
            "success" => false,
            "message" => "Invalid game ID."
        ]));
    }

    $pass = false; // Whether we should pass it to the matchmaker or not.
                   // If this is set to true, it will be passed and a result
                   // will be returned by the matchmaker.

    $statement = $sql->prepare("SELECT * FROM `jobs` WHERE `game` = ? AND `deleted` = 0");
    $statement->execute([$_GET[$id]]);
    $jobs = $statement->fetch(PDO::FETCH_ASSOC);
    $jobs_rowcount = $statement->rowCount();

    $statement = $sql->prepare("SELECT * FROM `games` WHERE `id` = ? AND `deleted` = 0");
    $statement->execute([$_GET[$id]]);
    $game = $statement->fetch(PDO::FETCH_ASSOC);
    $game_rowcount = $games->rowCount();

    close_database_connection($sql, $statement); // We're done with database stuff here, lets close it early
                                                 // so that the matchmaker doesn't potentially error.

    if ($games_rowcount <= 0)
    {
        exit(json_encode([
            "success" => false,
            "message" => "Game does not exist."
        ]));
    }

    $jobID = create_job_id();

    if ($jobs_rowcount <= 0 && $game["last_start"] >= time() - 5000)
    {
        $pass = true;
    }
    else if ($jobs_rowcount <= 0)
    {
        // Create a job
        $statement = $sql->prepare("INSERT INTO jobs(`name`, `port`, `game`, `started`, `expiration`, `version`) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $statement->execute([$jobID, $_GET["id"], time(), time() + 604800, "2017"]);

        open_job(array_rand(ENVIRONMENT["GAMESERVER"]["IPS"]), $jobID, [
            "id" => $jobID,
            "expiration" => 604800,
            "cores" => 1,
            "category" => $_GET["id"],
            "script" => "loadstring(game:HttpGet('". get_server_host() . "/api/rbx/game/server/gameserver?data=". $_GET["id"] . ";0;". $jobID . ";". get_server_host() . "', true))()"
        ]);
        
        // Pass
        $pass = true;
    }
    else
    {
        // my brain has reached full capactiy. i'll finish this later
        
        $statement = $sql->prepare("INSERT INTO game_tokens(`token`, `generated`, `user_id`, `game_id`, `place_id`, `ip`, `port`) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $payload = "2017"; 
        $payload = "rboxlo://" . base64_encode($payload);

        exit(json_encode([
            "success" => true,
            "payload" => $payload
        ]));
    }

    if ($pass)
    {
        // Now pass to the matchmaker
        
        $options = [
            "http" => [
                "method"  => "POST",
                "content" => json_encode(["id" => $_GET["id"]]),
                "header"  => "Content-Type: application/json\r\n"
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents(ENVIRONMENT["GAMESERVER"]["MATCHMAKER"], false, $context);

        exit($result);
    }
?>