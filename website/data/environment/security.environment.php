<?php
    /* 
        >>> Security

        SECURITY["CRYPT"]["KEY"] => The private cryption key that will be used to protect user details.
                                    ** DO NOT LET THIS LEAK . ** If you make this information public, should your database get compromised, the protection becomes useless.
        SECURITY["CRPYT"]["HASH"] => The default hashing algorithm for user information encryption. The default is sha512. Change it if you want.
        SECURITY["KEY"] => *** DO NOT CHANGE THIS. *** This reads from key.pem which is in the data folder. Change the contents within that file to a base64 encoded private key.
    */

    define("SECURITY", [
        "CRYPT" => [
            "HASHING" => "sha512",
            "ENCRYPTION" => "aes-256-cbc",
            "KEY" => ""
        ],
        "KEY" => file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../data/key.pem")
    ]);
?>