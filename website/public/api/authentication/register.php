<?php 
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
    header("Content-Type: application/json");

    if (!PROJECT["PRIVATE"]["IMPLICATION"])
    {
        exit(json_encode([
            "success" => false,
            "message" => PROJECT["NAME"] . " is currently not open to the public at this time."
        ]));
    }

    open_database_connection($sql);

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
        $message = "You cannot create new accounts while logged in!";
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

        if ((!isset($information["recaptcha"]) || empty($information["recaptcha"])) && !$error)
        {
            $message = "Please solve the captcha.";
            $error = true;
        }

        if (!$error)
        {
            if (!$error)
            {
                // Password, username, and E-mail simple validation
                $username = $information["username"];
                $password = $information["password"];
                $confirmed_password = $information["confirmed_password"];
                $email = $information["email"];
                
                // Username validation
                if ((strlen($username) == 0 || empty($username)) && !error)
                {
                    $message = "In order to create an account on ". PROJECT["NAME"] .", you must enter a username.";
                    $error = true;
                }

                if (!ctype_alnum($information["username"]) && !$error)
                {
                    $message = "You cannot create an account with a username that contains special characters.";
                    $error = true;
                }

                if (strlen($username) < 3 && !$error)
                {
                    $message = "Your username has to be at least 3 characters or more.";
                    $error = true;
                }

                if (strlen($username) > 20 && !$error)
                {
                    $message = "Your username is too long (more than 20 characters.)";
                    $error = true;
                }

                // E-Mail validation
                if ((strlen($email) == 0 || empty($email)) && !$error)
                {
                    $message = "In order to create an account on ". PROJECT["NAME"] .", you must enter an E-Mail.";
                    $error = true;
                }

                $email = @str_replace(str_replace(strstr($email, "@"), "", strstr($email, "+")), "", $email); // strip the tag from address, e.g john+alt@gmail.com -> john@gmail.com
                $email = @filter_var(trim($information["email"]), FILTER_SANITIZE_EMAIL);

                if ((!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) && !$error)
                {
                    $message = "Invalid E-Mail.";
                    $error = true;
                }

                if (!$error)
                {
                    $domain_name = @substr(strrchr($email, "@"), 1);

                    if (!in_array($domain_name, PROJECT["VALID_EMAIL_DOMAINS"]) && !$error)
                    {
                        $message = "Please register using a known E-Mail provider.";
                        $error = true;
                    }

                    if (strlen($email) > 128 && !$error)
                    {
                        $message = "Your E-Mail cannot exceed 128 characters.";
                        $error = true;
                    }

                    // Password validation
                    if ((strlen($password) == 0 || empty($password)) && !$error)
                    {
                        $message = "In order to create an account on ". PROJECT["NAME"] .", you must enter a password.";
                        $error = true;
                    }

                    if ((strlen($confirmed_password) == 0 || empty($confirmed_password)) && !$error)
                    {
                        $message = "You must confirm your password.";
                        $error = true;
                    }

                    if ($confirmed_password !== $password && !$error)
                    {
                        $message = "Passwords do not match.";
                        $error = true;
                    }

                    if (strlen($password) < 8 && !$error)
                    {
                        $message = "Your password must be longer than 8 characters.";
                        $error = true;
                    }

                    // Validate stuff using DB such as if the username is taken, the E-mail is already taken, >3 accounts per IP, etc.
                    if (!$error)
                    {
                        $statement = $sql->prepare("SELECT * FROM `users` WHERE `username` = ?");
                        $statement->execute([$username]);
                        $result = $statement->fetch(PDO::FETCH_ASSOC);

                        if ($result)
                        {
                            $message = "The username you have chosen is taken. Please try another one.";
                            $error = true;
                        }

                        if (!$error)
                        {
                            $email = _crypt($email);

                            $statement = $sql->prepare("SELECT * FROM `users` WHERE `email` = ?");
                            $statement->execute([$email]);
                            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
                            if ($result)
                            {
                                $message = "There already exists an account with this E-Mail.";
                                $error = true;
                            }

                            if (!$error)
                            {
                                $ip = _crypt(get_user_ip());

                                $statement = $sql->prepare("SELECT * FROM `users` WHERE `register_ip` = ? OR `last_ip` = ?");
                                $statement->execute([$username]);

                                if ($statement->rowCount() > 3)
                                {
                                    $message = "You can only have 3 accounts per IP address.";
                                    $error = true;
                                }

                                // invite
                                if (PROJECT["PRIVATE"]["INVITE_ONLY"] && !$error)
                                {
                                    if (empty($information["invite_key"]) || !isset($information["invite_key"]))
                                    {
                                        $message = "You need an invite key to register on ". PROJECT["NAME"] .".";
                                        $error = true;
                                    }

                                    if (!ctype_alnum($information["invite_key"]) && !$error)
                                    {
                                        $message = "Invalid invite key.";
                                        $error = true;
                                    }
                                    
                                    if (!$error)
                                    {
                                        $statement = $sql->prepare("SELECT `uses`, `max_uses` FROM `invite_keys` WHERE `key` = ?");
                                        $statement->execute([$information["invite_key"]]);
                                        $result = $statement->fetch(PDO::FETCH_ASSOC);

                                        if (!$result)
                                        {
                                            $message = "That invite key doesn't exist.";
                                            $error = true;
                                        }

                                        if (!$error)
                                        {
                                            if ($result["uses"] >= $result["max_uses"])
                                            {
                                                $message = "That invite key has already been used.";
                                                $error = true;
                                            }

                                            if (!$error)
                                            {
                                                // Mark key as used
                                                $statement = $sql->prepare("UPDATE `invite_keys` SET `uses` = `uses` + 1 WHERE `key` = ?");
                                                $statement->execute([$information["invite_key"]]);
                                            }
                                        }
                                    }
                                }

                                if (!$error)
                                {
                                    // hash pw
                                    $password = password_hash($password, PASSWORD_ARGON2ID);

                                    // default wearing thing
                                    $avatar = json_encode([
                                        "colors" => [
                                            "head" => "24",
                                            "torso" => "23",
                                            "left_arm" => "24",
                                            "right_arm" => "24",
                                            "left_leg" => "119",
                                            "right_leg" => "119"
                                        ],
                                        "package" => "r6_default",
                                        "head" => "",
                                        "face" => "",
                                        "hats" => [],
                                        "gears" => [],
                                        "tshirt" => "",
                                        "shirt" => "",
                                        "pants" => "",
                                        "r15_animations" => [
                                            "emotes" => [],
                                            "walk" => "",
                                            "run" => "",
                                            "fall" => "",
                                            "jump" => "",
                                            "swim" => "",
                                            "climb" => "",
                                            "idle" => ""
                                        ],
                                        "r15_avatar_scaling" => [
                                            "width" => "1.0000",
                                            "height" => "1.0000",
                                            "head" => "1.0000",
                                            "depth" => "1.00",
                                            "proportion" => "0.0000",
                                            "body_type" => "0.0000"
                                        ],
                                        "avatar_type" => "r6"
                                    ]);

                                    $preferences = json_encode([
                                        "blurb" => "Hi! I'm new to " . PROJECT["NAME"] . "."
                                    ]);

                                    // permissions
                                    $permissions = json_encode([
                                        "communication" => true, // ssc
                                        "admin" => [
                                            "moderation" => [
                                                "onsite" => false,
                                                "ingame" => false
                                            ],
                                            "see_panel" => false,
                                            "deploy_versions" => false,
                                            "see_errors" => false
                                        ]
                                    ]);

                                    // Crypt
                                    $email = _crypt($email);
                                    $ip = _crypt($ip);
                                    $password = _crypt($password); // dumb 2-step cryption: plaintext -> argon -> crypt

                                    // Create account
                                    $statement = $sql->prepare("INSERT INTO `users` (`username`, `password`, `email`, `register_ip`, `last_ip`, `money`, `joindate`, `avatar`, `email_verified`, `preferences`, `last_reward`, `permissions`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    $statement->execute([$username, $password, $email, $ip, $ip, 100, time(), $avatar, 0, $preferences, time(), $permissions]);

                                    // Get user
                                    $statement = $sql->prepare("SELECT * FROM `users` WHERE `username` = ?");
                                    $statement->execute([$username]);
                                    $result = $statement->fetch(PDO::FETCH_ASSOC);

                                    // Set session
                                    $_SESSION["user"] = $result;
                                    $_SESSION["user"]["password"] = ""; // dont keep the hash in session just in case

                                    // Parse
                                    $_SESSION["user"]["permissions"] = json_decode($result["permissions"], true);
                                    $_SESSION["user"]["avatar"] = json_decode($result["avatar"], true);

                                    // Copy default thumbnail for this user
                                    copy($_SERVER["DOCUMENT_ROOT"] . "/../data/thumbnails/users/0.png", $_SERVER["DOCUMENT_ROOT"] . "/../data/thumbnails/users/" . $_SESSION["user"]["id"] . ".png");
                                    
                                    // Return success
                                    $success = true;
                                    $message = "Welcome to ". PROJECT["NAME"] .", ". $username ."! Redirecting you to your dashboard...";
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    close_database_connection($sql, $statement);
    
    exit(json_encode([
        "success" => $success,
        "message" => $message
    ]));
?>