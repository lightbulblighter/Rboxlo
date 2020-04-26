<?php
    // Session security
    ini_set("session.use_strict_mode", true);
    ini_set("session.use_cookies", true);
    ini_set("session.use_only_cookies", true);

    ini_set("session.cookie_httponly", true);
    ini_set("session.cookie_secure", true);

    ini_set("session.hash_function", "sha256");
    ini_set("session.hash_bits_per_character", 5);

    ini_set("session.entropy_file", "/dev/urandom");
    ini_set("session.entropy_length", 64);

    session_start();

    if (!isset($_SESSION["canary"]))
    {
        session_regenerate_id(true);
        $_SESSION["canary"] = time();
    }

    if ($_SESSION["canary"] < time() - 300)
    {
        session_regenerate_id(true);
        $_SESSION["canary"] = time();
    }

    date_default_timezone_set(TIMEZONE);

    if (MAINTENANCE || $_SESSION["user"]["id"] == 1)
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

    if (MAINTENANCE)
    {
        if (!in_array(get_user_ip(), MAINTENANCE_IPS))
        {
            exit(BASE_NAME . " is currently under maintenance. Please check back soon!");
        }
    }
?>