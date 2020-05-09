<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

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

        $statement = "SELECT * FROM `game_counted_statistics` WHERE `name` = ?";
        $statement->execute([$_POST["counterName"]]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result)
        {
            // Create counter
            $statement = "INSERT INTO `game_counted_statistics` (`version`, `name`, `count`, `ip`) VALUES (?, ?, ?, ?)";
            $statement->execute([$key["version"], $_POST["counterName"], 1], get_user_ip());
        }
        else
        {
            // Increment counter
            $statement = "UPDATE `game_counted_statistics` SET `count` = `count` + 1 WHERE `name` = ? AND `ip` = ?";
            $statement->execute([$_POST["counterName"], get_user_ip()]);
        }
    }
    
    // Finish
    exit("OK");
?>