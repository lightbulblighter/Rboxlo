<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx.php");

    header("Content-Type: application/json");

    // unlike other Settings/QuietGet endpoints, this *will* check if there is an apiKey
    // if there isn't, it errors
    
    $key = get_api_key($_GET["apiKey"]);

    if (!$key || $key["usage"] !== "get_rccservice_settings")
    {
        exit("Invalid API key");
    }

    exit(get_fflags($key["version"], "RCCService"));
?>