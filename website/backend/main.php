<?php
    // The cookie's name is random so that there is no "PHPSESSID" stealer
    session_name(strtolower(ENVIRONMENT["PROJECT"]["NAME"]) . "_session");

    session_start();

    if (!isset($_SESSION["canary"]))
    {
        session_regenerate_id();
        $_SESSION["canary"] = time();
    }

    if ($_SESSION["canary"] < time() - 300)
    {
        session_regenerate_id();
        $_SESSION["canary"] = time();
    }

    // If the session's IP is different, then exit to prevent cookie hijacking
    if (!isset($_SESSION["ip"]) || empty($_SESSION["ip"]) || $_SESSION["ip"] !== get_user_ip())
    {
        session_clear();
        session_start();

        $_SESSION["ip"] = get_user_ip();
    }

    // .ROBLOSECURITY is tied to the session, but does not have session "logged in" details
    $_SESSION["roblox"] = hash("sha512", strtoupper(bin2hex(random_bytes(585))));
    setcookie(".ROBLOSECURITY", $_SESSION["roblox"]);

    date_default_timezone_set(TIMEZONE);

    if ($_SESSION["user"]["permissions"]["see_errors"])
    {
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        error_reporting(E_ALL);
    }
    else
    {
        error_reporting(0);
    }

    if (!isset($_SESSION["csrf"]) || empty($_SESSION["csrf"]))
    {
        $_SESSION["csrf"] = hash("sha256", bin2hex(random_bytes(64)));
    }
?>