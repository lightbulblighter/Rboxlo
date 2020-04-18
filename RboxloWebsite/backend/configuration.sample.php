<?php
    /* SQL */
    define("SQL_DB_USER", "root");
    define("SQL_DB_PASS", "");

    define("SQL_DB_NAME", "rboxlo");
    define("SQL_DB_HOST", "localhost");
    define("SQL_DB_PORT", "3306");

    /* BASE */
    define("BASE_NAME", "Rboxlo");
    define("BASE_URL", "rb.ozzt.pw");
    define("BASE_VERSION", "1.0.0");
    define("BASE_PATH", "/var/www");
    
    /* CRYPT */
    define("CRYPT_KEY", "");
    define("CRYPT_HASH_ALGO", "sha512");
    define("CRYPT_ENCRYPTION", "aes-256-cbc");

    /* MAINTENANCE */
    define("MAINTENANCE", false);
    define("MAINTENANCE_IPS", ["127.0.0.1", "localhost", "::1"]);

    /* RECAPTCHA */
    define("RECAPTCHA_PUBLIC_KEY", "");
    define("RECAPTCHA_PRIVATE_KEY", "");

    /* EMAIL */
    define("EMAIL_USERNAME", "");
    define("EMAIL_PASSWORD", "");

    /* misc */ 
    define("TIMEZONE", "America/Chicago");
    define("PROFANITY", [""]);
    define("INVITE_ONLY", false);
    define("GITHUB_URL", "https://www.github.com/lighterlightbulb/Rboxlo");
    define("VALID_EMAIL_DOMAINS", ["google.com", "protonmail.ch", "rb.ozzt.pw", "ozzt.pw", "googlemail.com", "gmail.com", "yahoo.com", "yahoomail.com", "protonmail.com", "outlook.com", "hotmail.com", "microsoft.com", "inbox.com", "mail.com", "zoho.com"])
?>