<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx.php");

    header("Content-Type: text/plain");

    $valid_key = "";

    foreach ($_GET as $key => $_)
    {
        if (ctype_digit(substr($key, 4))) // If the $_GET key's letters after the first 4 are integers
        {
            $valid_key = get_api_key(substr($key, 0, 4)); // Get the api key for it, and trim letters after the first four
        }
    }

    if (!$valid_key)
    {
        exit("Version doesn't exist.");
    }
    
    // Get latest version
    $statement = $GLOBALS["sql"]->prepare("SELECT `textual_version` FROM `client_versions` WHERE `latest` = 1 AND `year` = ?");
    $statement->execute([$valid_key["version"]]);

    // Return
    exit($statement->fetch(PDO::FETCH_ASSOC)["textual_version"]);
?>