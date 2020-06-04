<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx.php");

    header("Content-Type: text/plain");

    if (isset($_GET["apiKey"])) // Counters/Increment
    {
        $key = get_api_key($_GET["apiKey"]);

        if (!$key || $key["usage"] !== "ephemeral_counters")
        {
            exit("Invalid API key");
        }

        if (!isset($_GET["counterName"]) || empty($_GET["counterName"]))
        {
            exit("No counter name specified");
        }

        if (!ctype_alnum($_POST["counterName"]))
        {
            exit("Invalid counter name");
        }

        $counter_name = filter_var($_POST["counterName"], FILTER_SANITIZE_STRING);

        open_database_connection($sql);

        $statement = $sql->prepare("SELECT * FROM `game_counted_statistics` WHERE `name` = ?");
        $statement->execute([$counter_name]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result)
        {
            // Create counter
            $statement = "INSERT INTO `game_counted_statistics` (`version`, `name`, `ip`) VALUES (?, ?, ?, ?)";
            $statement->execute([$key["version"], $counter_name, get_user_ip()]);
        }
        else
        {
            // Increment counter
            $statement = "UPDATE `game_counted_statistics` SET `count` = `count` + 1 WHERE `name` = ? AND `ip` = ?";
            $statement->execute([$counter_name, get_user_ip()]);
        }

        close_database_connection($sql, $statement);
    }
    
    // Finish
    exit("OK");
?>