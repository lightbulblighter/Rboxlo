<?php 
    require_once("/var/www/backend/includes.php");
    
    header("Content-Type: text/plain");

    // Defaults to an error
    $success = false;
    $message = "An unexpected error occurred.";

    $error = false; // This variable is set so we don't perform additional checks if we already know that something is invalid.
                    // However, one issue with this is that we have to have nested if-else cases.
                    // It sucks, but that's life.

    if (isset($_POST["information"]))
    {
        $information = json_decode($_POST["information"], true);
        
        if (!isset($information["username"]) || !isset($information["password"]) || !isset($information["csrf"]) || empty($information["username"]) || empty($information["password"]) || empty($information["csrf"]))
        {
            $message = "One of the required values for authentication was not received, or was not sent.";
            $error = true;
        }

        if ($information["csrf"] !== $_SESSION["csrf"] && !$error)
        {
            $message = "Invalid CSRF token.";
            $error = true;
        }

        if (!$error)
        {
            $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `users` WHERE username = ? OR email = ?");
            $statement->execute([$information["username"], $information["username"]]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result)
            {
                if (password_verify($information["password"], $result["password"]))
                {
                    $_SESSION["user"] = $result;
                    $_SESSION["user"]["password"] = "";

                    if (!file_exists(BASE_PATH ."/html/renders/users/". $_SESSION["user"]["id"] .".png"))
                    {
                        copy(BASE_PATH ."/html/renders/users/0.png", BASE_PATH ."/html/renders/users/". $_SESSION["user"]["id"] .".png");
                    }

                    $success = true;
                    $message = "Welcome back, ". $result["username"] ."! Redirecting you to your dashboard...";
                }
                else
                {
                    $message = "Invalid credentials!";
                }
            }
            else
            {
                $message = "That user could not be found!";
            }
        }
    }
    else
    {
        $message = "Nothing was sent.";
    }

    exit(json_encode([
        "success" => $success,
        "message" => $message
    ]));
?>