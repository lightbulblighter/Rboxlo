<?php
    require_once("/var/www/backend/includes.php");

    require_once("/var/www/backend/phpmailer/src/Exception.php");
    require_once("/var/www/backend/phpmailer/src/PHPMailer.php");
    require_once("/var/www/backend/phpmailer/src/SMTP.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

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

        if (!isset($_SESSION["user"]))
        {
            $message = "You need to be logged in in order to verify your E-Mail address.";
            $error = true;
        }

        if ($information["send"])
        {
            // Create a token (or key)
            $token = hash("sha256", bin2hex(random_bytes(64)));

            $statement = $sql->prepare("INSERT INTO `email_verification_keys` (`key`, `generated`, `uid`) VALUES (?, ?, ?)");
            $statement->execute([$token, time(), $_SESSION["user"]["id"]]);

            // Get user's email
            $statement = $sql->prepare("SELECT `email` FROM `users` WHERE `id` = ?");
            $statement->execute([$_SESSION["user"]["id"]]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $user_email_alias = substr($result["email"], 0, strpos($result["email"], "@")); // alias ("john@gmail.com" -> "john")

            // Send E-Mail
            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->SMTPDebug = off;
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL_USERNAME;
            $mail->Password = EMAIL_PASSWORD;
            
            $mail->setFrom(EMAIL_USERNAME, BASE_NAME);
            $mail->addReplyTo(EMAIL_USERNAME, BASE_NAME);

            $mail->addAddress($result["email"], $user_email_alias);

            $mail->Subject = BASE_NAME . " Verification for ". $_SESSION["user"]["username"];

            $mail->msgHTML(
                    "<h3>". BASE_NAME ."</h3><br><br>Hi ". $_SESSION["user"]["username"] .", please verify your E-Mail address at https://". BASE_URL ."/my/verify?token=". $token ."<br><br>Not ". $_SESSION["user"]["username"] ."? Ignore this message."
                );

            if ($mail->send())
            {
                $message = "Successfully sent E-Mail!";
                $success = true;
            }
            else
            {
                $message = "An unexpected error occurred.";
            }
        }
        else
        {
            if (empty($information["verification_key"]) || !isset($information["verification_key"]) && !$error)
            {
                $message = "You need to specify a verification key.";
                $error = true;
            }

            if (!ctype_alnum($information["verification_key"]) || strlen($information["verification_key"] !== 64 && !$error)
            {
                $message = "Invalid verification key.";
                $error = true;
            }

            // funny db stuff
            if (!$error)
            {
                $statement = $GLOBALS["sql"]->prepare("SELECT `used`, `generated`, `uid` FROM `email_verification_keys` WHERE `key` = ?");
                $statement->execute([$information["key"]]);
                $result = $statement->fetch(PDO::FETCH_ASSOC);

                if (!$result)
                {
                    $message = "That verification key doesn't exist.";
                    $error = true;
                }

                if (!$error)
                {
                    if ($result["uses"] >= 1 && !$error)
                    {
                        $message = "That verification key has already been used. Please use a new one.";
                        $error = true;
                    }

                    if (!$error)
                    {
                        $elapsed = time() - $result["generated"];
                        if ($elapsed >= 900)
                        {
                            $message = "That verification key has expired (15 minute timeout.) Please use a new one.";
                            $error = true;
                        }

                        // passed all checks - do this:
                        // -> set key as used
                        // -> set user as verified
                        if (!$error)
                        {
                            $statement = $GLOBALS["sql"]->prepare("UPDATE `users` SET `email_verified` = 1 WHERE `id` = ?");
                            $statement->execute([$result["uid"]]);

                            $statement = $GLOBALS["sql"]->prepare("DELETE FROM `email_verification_keys` WHERE `generated` = ?");
                            $statement->execute([$result["key"]]);

                            $message = "Successfully verified user! Redirecting you back to your dashboard...";
                            $success = true;
                        }
                    }
                }
            }
        }
    }
    else
    {
        $message = "Nothing was sent.";
    }


?>