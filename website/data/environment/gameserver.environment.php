<?php
    /* 
        >>> Gameserver

        GAMESERVER["IPS"] => An array containing valid IP addresses for gameservers.
        GAMESERVER["MATCHMAKER"] => The IP address for the matchmaker.
    */
    
    define("GAMESERVER", [
        "IPS" => [
            "127.0.0.1",
            "localhost"
        ],
        "MATCHMAKER" => "127.0.0.1:3000"
    ]);
?>