<?php 
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");
    
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
                // See if password is old non-crypted password
                if (!is_base64($result["password"]))
                {
                    // Update values to be crypted
                    $statement = $GLOBALS["sql"]->prepare("UPDATE `users` SET `email` = ?, `password` = ?, `last_ip` = ?, `register_ip` = ? WHERE `id` = ?");
                    $statement->execute([$result["email"], $result["password"], $result["last_ip"], $result["register_ip"]]);

                    // Get new values!
                    $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `users` WHERE `id` = ?");
                    $statement->execute([$result["id"]]);

                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                }

                if (password_verify($information["password"], _crypt($result["password"], "decrypt")))
                {
                    // See if password needs rehashing (I didn't use insecure hashing like md5 or sha1, this is to convert Argon2i hashes to Argon2id)
                    // Check commit history if you're skeptical ¯\_(ツ)_/¯
                    if (password_get_info($result["password"])["algoName"] !== "argon2id")
                    {
                        // Update
                        $statement = $GLOBALS["sql"]->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");
                        $statement->execute([password_hash($information["password"], PASSWORD_ARGON2ID), $result["id"]]);
                    }

                    $_SESSION["user"] = $result;
                    $_SESSION["user"]["password"] = "";

                    // Crypt
                    $_SESSION["user"]["email"] = _crypt($_SESSION["user"]["email"], "decrypt");
                    $_SESSION["user"]["last_ip"] = _crypt($_SESSION["user"]["last_ip"], "decrypt");
                    $_SESSION["user"]["register_ip"] = _crypt($_SESSION["user"]["register_ip"], "decrypt");

                    if (!file_exists(ROOT . "/renders/users/" . $_SESSION["user"]["id"] . ".png"))
                    {
                        copy(ROOT . "/renders/users/0.png", ROOT . "/renders/users/" . $_SESSION["user"]["id"] .".png");
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