<?php
    /* SQL Credentials */
    define("SQL_DB_USER", "root");
    define("SQL_DB_PASS", "");

    define("SQL_DB_NAME", "rboxlo");
    define("SQL_DB_HOST", "localhost");
    define("SQL_DB_PORT", "3306");

    /* Google-Specific */
    define("G_RECAPTCHA_PUBLIC_KEY", "");
    define("G_RECAPTCHA_PRIVATE_KEY", "");
    define("G_ANALYTICS_TAG", "");

    /* Base Configuration */
    define("BASE_NAME", "Rboxlo");
    define("BASE_URL", "rb.ozzt.pw");
    
    /* Rboxlo Crypt Credentials */
    define("CRYPT_KEY", "");
    define("CRYPT_HASH_ALGO", "sha512");
    define("CRYPT_ENCRYPTION", "aes-256-cbc");

    /* Maintenance */
    define("MAINTENANCE", false);
    define("MAINTENANCE_IPS", ["127.0.0.1", "localhost", "::1"]);

    /* Official E-Mail Account Credentials */
    define("EMAIL_USERNAME", "");
    define("EMAIL_PASSWORD", "");

    /* Etcetera */ 
    define("TIMEZONE", "America/Chicago");
    define("PROFANITY", [""]);
    define("INVITE_ONLY", false);
    define("GITHUB_URL", "https://www.github.com/lighterlightbulb/Rboxlo");
    define("VALID_EMAIL_DOMAINS", ["google.com", "protonmail.ch", "rb.ozzt.pw", "ozzt.pw", "googlemail.com", "gmail.com", "yahoo.com", "yahoomail.com", "protonmail.com", "outlook.com", "hotmail.com", "microsoft.com", "inbox.com", "mail.com", "zoho.com"])
?>