<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx/script.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/rbx/soap.php");

    function is_profane($text)
    {
        foreach (PROFANITY as $bad_word)
        {
            if (strpos($text, $bad_word) !== false)
            {
                return true;
            }
        }

        return false;
    }
    
    function filter_profanity($text)
    {
        return($text);
    }

    function get_signature($script)
    {
        $key = file_get_contents("/var/www/static/key.pem");
        $signature;

        openssl_sign($script, $signature, $key, OPENSSL_ALGO_SHA1);

        return "%%" . $signature . "%%";
    }
?>