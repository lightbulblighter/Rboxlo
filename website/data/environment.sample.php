<?php
    /* 
        This is the project's environment file. Configure this for your environment.
        The details below should provide a thorough explanation on how to configure your environment to run Rboxlo.

        >>>> Database

        SQL["USERNAME"] => The username for the user that has *full* read/write access to the project's database.
        SQL["PASSWORD"] => The password for the database's user.
        SQL["DATABASE"] => The database's name.
        SQL["HOSTNAME"] => The IP address where the database is hosted.
        SQL["PORT"] => The port of the IP in which the database is hosted.

        >>> Google

        =========================>
        ==> NOTE: Rboxlo currently uses Invisible reCAPTCHA (v2.)
        ==> Make sure that the reCAPTCHA keys are Invisible reCAPTCHA v2 keys.
        =========================>

        GOOGLE["ANALYTICS_TAG"] => The Google Analytics tag for this website. Leave it blank if you don't want analytics.
        GOOGLE["RECAPTCHA"]["PUBLIC_KEY"] => (AKA SITE KEY) The reCAPTCHA public key. This can be obtained from the reCAPTCHA admin console.
        GOOGLE["RECAPTCHA"]["PRIVATE_KEY"] => (AKA SECRET KEY) The reCAPTCHA private key. This can be obtained from the reCAPTCHA admin console.

        >>> Project

        PROJECT["NAME"] => The project's name.
        PROJECT["CURRENCY"] => The project's currency's name.
        PROJECT["REFERRAL"] => Whether ot not the project is on "invite-only" mode.

        >>> Security

        SECURITY["CRYPT"]["KEY"] => The private cryption key that will be used to protect user details.
                                    ** DO NOT LET THIS LEAK . ** If you make this information public, should your database get compromised, the protection becomes useless.
        SECURITY["CRPYT"]["HASH"] => The default hashing algorithm for user information encryption. The default is sha512. Do not change it.
        SECURITY["PASSWORD"] => The password hashing algorithm. Default is Argon2id
        SECURITY["SIGNATURES"] => *** DO NOT CHANGE THIS. *** This reads from key.pem which is in the same folder as this file. Change the contents within that file to a base64 encoded private key.
        
        >>> Repository

        REPOSITORY["URL"] => The URL to the GitHub repository.
        REPOSITORY["NAME"] => The name of the repository. <repository_name>
        REPOSITORY["FULL_NAME"] => The full name of the repository. <owner>/<repository/name>

        >>> Official E-Mail Account Credentials

        EMAIL["ADDRESS"] => The E-Mail address of the official account that sends verification messages.
        EMAIL["PASSWORD"] => The password for said E-Mail address.

        This will usually be set up as a Yandere mailserver.

        >>> Miscallaneous

        TIMEZONE => A string indicating the timezone that the server is being hosted in.
        VALID_EMAIL_DOMAINS => An array containing the valid E-Mail domains that a user can sign up with (for example, with john@gmail.com, @gmail.com is the E-Mail domain)
    */
    
    define("ENVIRONMENT", [ 

        "SQL" => [
            "USERNAME" => "root",
            "PASSWORD" => "",
            "DATABASE" => "rboxlo",
            "HOST"     => "localhost",
            "PORT"     => "3306"
        ],

        "GOOGLE" => [
            "ANALYTICS_TAG" => "",
            "RECAPTCHA" => [
                "PUBLIC_KEY" => "",
                "PRIVATE_KEY" => ""
            ]
        ],

        "PROJECT" => [
            "NAME" => "Rboxlo",
            "CURRENCY" => "Rbux",
            "DISCORD" => "https://discord.gg/W5eCJk9",
            "INVITE_ONLY" => false
        ],

        "SECURITY" => [
            "CRYPT" => [
                "HASH" => "sha512",
                "ENCRYPTION" => "aes-256-cbc",
                "KEY" => ""
            ],
            "PASSWORD" => PASSWORD_ARGON2ID,
            "SIGNATURES" => file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../data/key.pem")
        ],

        "EMAIL" => [
            "ADDRESS" => "",
            "PASSWORD" => ""
        ],

        "REPOSITORY" => [
            "URL" => "https://www.github.com/lighterlightbulb/Rboxlo",
            "NAME" => "Rboxlo",
            "FULL_NAME" => "lightbulblighter/Rboxlo"
        ],

        "TIMEZONE" => "America/Chicago",
        "VALID_EMAIL_DOMAINS" => ["rboxlo.xyz", "google.com", "protonmail.ch", "googlemail.com", "gmail.com", "yahoo.com", "yahoomail.com", "protonmail.com", "outlook.com", "hotmail.com", "microsoft.com", "inbox.com", "mail.com", "zoho.com"]
    ]);
?>