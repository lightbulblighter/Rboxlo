<?php
    /* 
        This is the project's environment file. Configure this for your environment.
        The details below should provide a thorough explanation on how to configure your environment to run Rboxlo.
        
        >>> Google

        =========================>
        ==> NOTE: Rboxlo currently uses Invisible reCAPTCHA (v2.)
        ==> Make sure that the reCAPTCHA keys are Invisible reCAPTCHA v2 keys.
        =========================>

        GOOGLE["ANALYTICS"]["ENABLED"] => Whether to use Google Analytics or not.
        GOOGLE["ANALYTICS"]["TAG"] => The Google Analytics tag for this website. Leave it blank if you don't want analytics.
        GOOGLE["RECAPTCHA"]["PUBLIC_KEY"] => (AKA SITE KEY) The reCAPTCHA public key. This can be obtained from the reCAPTCHA admin console.
        GOOGLE["RECAPTCHA"]["PRIVATE_KEY"] => (AKA SECRET KEY) The reCAPTCHA private key. This can be obtained from the reCAPTCHA admin console.

        >>> Project

        PROJECT["NAME"] => The project's name.
        PROJECT["CURRENCY"] => The project's currency's name.
        PROJECT["REFERRAL"] => Whether to not the project is on "invite-only" mode.
        PROJECT["DEBUGGING"] => Whether to show error messages or not, regardless of user identity.

        >>> Security

        SECURITY["CRYPT"]["KEY"] => The private cryption key that will be used to protect user details.
                                    ** DO NOT LET THIS LEAK . ** If you make this information public, should your database get compromised, the protection becomes useless.
        SECURITY["CRPYT"]["HASH"] => The default hashing algorithm for user information encryption. The default is sha512. Do not change it.
        SECURITY["SIGNATURES"] => *** DO NOT CHANGE THIS. *** This reads from key.pem which is in the same folder as this file. Change the contents within that file to a base64 encoded private key.
        
        >>> Repository

        REPOSITORY["URL"] => The URL to the GitHub repository.
        REPOSITORY["NAME"] => The name of the repository. <repository_name>
        REPOSITORY["FULL_NAME"] => The full name of the repository. <owner>/<repository/name>

        >>> E-Mail Verification

        EMAIL["ADDRESS"] => The E-Mail address of the official account that sends verification messages.
        EMAIL["PASSWORD"] => The password for said E-Mail address.
        EMAIL["SERVER"]["ADDRESS"] => The address for the E-Mail SMTP server.
        EMAIL["SERVER"]["PORT"] => The port for the E-Mail SMTP server.

        Rboxlo is built to use a Yandex mailserver, so usually that will work best.

        >>> Gameserver

        GAMESERVER["IPS"] => An array containing valid IP addresses for gameservers.
        GAMESERVER["MATCHMAKER"] => The IP address for the matchmaker.
        
        >>> Miscallaneous

        VALID_EMAIL_DOMAINS => An array containing the valid E-Mail domains that a user can sign up with (for example, with john@gmail.com, @gmail.com is the E-Mail domain)
    */
    
    define("ENVIRONMENT", [ 
        "GOOGLE" => [
            "ANALYTICS" => [
                "ENABLED" => false,
                "TAG" => ""
            ],
            "RECAPTCHA" => [
                "PUBLIC_KEY" => "",
                "PRIVATE_KEY" => ""
            ]
        ],

        "PROJECT" => [
            "NAME" => "Rboxlo",
            "CURRENCY" => "Rbux",
            "DISCORD" => "",
            "INVITE_ONLY" => false,
            "DEBUGGING" => true
        ],

        "SECURITY" => [
            "CRYPT" => [
                "HASH" => "sha512",
                "ENCRYPTION" => "aes-256-cbc",
                "KEY" => ""
            ],
            "SIGNATURES" => file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../data/key.pem")
        ],

        "EMAIL" => [
            "ADDRESS" => "",
            "PASSWORD" => "",
            "SERVER" => [
                "ADDRESS" => "",
                "PORT" => 587
            ]
        ],

        "REPOSITORY" => [
            "URL" => "https://www.github.com/lighterlightbulb/Rboxlo",
            "NAME" => "Rboxlo",
            "FULL_NAME" => "lightbulblighter/Rboxlo"
        ],

        "GAMESERVER" => [
            "IPS" => [
                "127.0.0.1",
                "localhost"
            ],
            "MATCHMAKER" => "127.0.0.1:3000"
        ],

        "VALID_EMAIL_DOMAINS" => ["rboxlo.xyz", "google.com", "protonmail.ch", "googlemail.com", "gmail.com", "yahoo.com", "yahoomail.com", "protonmail.com", "outlook.com", "hotmail.com", "microsoft.com", "inbox.com", "mail.com", "zoho.com"]
    ]);
?>