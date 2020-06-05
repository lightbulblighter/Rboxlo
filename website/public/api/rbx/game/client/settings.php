<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx.php");
    header("Content-Type: application/json");

    $version = "2017";
    
    if (isset($_GET["apiKey"])) // so we can get version, else default to 2017
    {
        $key = get_api_key($_GET["apiKey"]);
        if (!$key || $key["usage"] !== "get_client_settings")
        {
            exit("Invalid API key");
        }

        $version = $key["version"];
    }

    exit(get_fflags($version, "ClientAppSettings"));
?>