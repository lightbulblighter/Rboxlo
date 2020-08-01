<?php
    /* 
        >>> Project

        PROJECT["NAME"] => The project's name.
        PROJECT["CURRENCY"] => The project's currency's name.
        PROJECT["DISCORD"] => An invite to the project's Discord.
        PROJECT["DEBUGGING"] => Whether to show error messages or not, regardless of user identity.
        PROJECT["COMMUNISM"] => Everything in the catalog is free.
        PROJECT["VALID_EMAIL_DOMAINS"] => An array containing the valid E-Mail domains that a user can sign up with (for example, with john@gmail.com, @gmail.com is the E-Mail domain)
        PROJECT["PRIVATE"]["LOCKDOWN"] => Complete lockdown of site if true
        PROJECT["PRIVATE"]["IMPLICATION"] => If false, removes register links, etc.
        PROJECT["PRIVATE"]["REFERRAL"] => Referral-based private revival: You need an invite key from a friend. Like Finobe
        PROJECT["PRIVATE"]["INVITE_ONLY"] => Invite-only-based private revival: You need a direct invite from an admin through a link
    */
    
    define("PROJECT", [
        "NAME" => "Rboxlo",
        "CURRENCY" => "Rbux",
        "DISCORD" => "",
        "DEBUGGING" => true,
        "COOKIE_POLICY" => "https://www.cookiepolicygenerator.com/live.php?token=Fdqho7wVRjAStnQAVbUsdIiT8UmsTOzR",
        "COMMUNISM" => false,
        "PRIVATE" => [
            "LOCKDOWN" => false,
            "IMPLICATION" => true,
            "REFERRAL" => false,
            "INVITE_ONLY" => false
        ],
        "VALID_EMAIL_DOMAINS" => ["rboxlo.xyz", "google.com", "protonmail.ch", "googlemail.com", "gmail.com", "yahoo.com", "yahoomail.com", "protonmail.com", "outlook.com", "hotmail.com", "microsoft.com", "inbox.com", "mail.com", "zoho.com"]
    ]);
?>