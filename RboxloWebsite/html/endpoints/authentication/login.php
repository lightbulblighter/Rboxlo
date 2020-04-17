<?php 
    require_once("/var/www/backend/includes.php");
    
    header("Content-Type: text/plain");

    // Defaults to an error
    $success = false;
    $message = "An unexpected error occurred.";

    $error = false; // This variable is set so we don't perform additional checks if we already know that something is invalid.
                    // However, one issue with this is that we have to have nested if-else cases.
                    // It sucks, but that's life.

    if (!isset($_POST["information"]))
    {
        $message = "Nothing was sent.";
        $error = true;
    }

    if (isset($_SESSION["user"]))
    {
        $message = "You are already logged in!";
        $error = true;
    }

    if (!$error)
    {
        $information = json_decode($_POST["information"], true);

        if ($information["csrf"] !== $_SESSION["csrf"] && !$error)
        {
            $message = "Invalid CSRF token.";
            $error = true;
        }

        if (!isset($information["username"]) || empty($information["username"]) || strlen($information["username"]) <= 0 && !$error)
        {
            $message = "In order to sign in, you need to specify a username.";
            $error = true;
        }

        if (!isset($information["password"]) || empty($information["password"]) || strlen($information["password"]) <= 0 && !$error)
        {
            $message = "In order to sign in, you need to specify a password.";
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

    exit(json_encode([
        "success" => $success,
        "message" => $message
    ]));
?>