<?php
    /* 
        >>> E-Mail Verification

        EMAIL["ADDRESS"] => The E-Mail address of the official account that sends verification messages.
        EMAIL["PASSWORD"] => The password for said E-Mail address.
        EMAIL["SERVER"]["ADDRESS"] => The address for the E-Mail SMTP server.
        EMAIL["SERVER"]["PORT"] => The port for the E-Mail SMTP server.

        Rboxlo is built to use a Yandex mailserver, so usually that will work best.
    */
    
    define("EMAIL", [
        "ADDRESS" => "",
        "PASSWORD" => "",
        "SERVER" => [
            "ADDRESS" => "",
            "PORT" => 587
        ]
    ]);
?>