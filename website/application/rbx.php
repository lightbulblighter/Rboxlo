<?php   
    // This file gets included by *all* Roblox endpoints.
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx/soap.php");

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
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment/security.environment.php");
        $signature;

        openssl_sign($script, $signature, SECURITY["KEY"], OPENSSL_ALGO_SHA1);

        return base64_encode($signature);
    }

    function get_api_key($key)
    {
        if (!isset($key) || empty($key) || !ctype_alnum(str_replace("-", "", $key)))
        {
            return false;
        }

        open_database_connection($sql);

        $statement = $sql->prepare("SELECT `version`, `usage` FROM `api_keys` WHERE `key` = ?");
        $statement->execute([$key]);
        
        close_database_connection($sql, $port);
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function get_fflags($version, $application)
    {
        return file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx/$version/fastflags/$application.json");
    }

    function open_job($ip, $port, $id, $information)
    {
        soap_send_envelope($ip, $port, "OpenJob", soap_get_envelope("OpenJob", [
            [
                "submethod" => "job",
                "details" => [
                    "id" => $information["id"],
                    "expirationInSeconds" => $information["expiration"],
                    "cores" => $information["cores"],
                    "category" => $information["category"]
                ]
            ],
            [
                "submethod" => "script",
                "details" => [
                    "name" => "Start Server",
                    "script" => $information["script"]
                ]
            ]
        ]));
    }

    function close_job($ip, $port, $id)
    {
        soap_send_envelope($ip, $port, "CloseJob", soap_get_envelope("CloseJob", [
            [
                "submethod" => "jobID",
                "content" => $id
            ]
        ]));
    }
?>