<?php
    // Debugging
        
    if (MAINTENANCE)
    {
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        error_reporting(E_ALL);
    }
    else
    {
        error_reporting(0);
    }

    ini_set("session.use_strict_mode", true); // Session security
    date_default_timezone_set(TIMEZONE);
    session_start();

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