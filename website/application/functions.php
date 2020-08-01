<?php
    function session_clear()
    {
        session_regenerate_id(true);
            
        session_unset();
        session_destroy();
        session_write_close();
    
        setcookie(session_name(), "", 0, "/");
    }

    function redirect($location)
    {
        header("Location: " . $location);
        exit();
    }

    function include_page($page)
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../public$page");
        exit();
    }

    function milliseconds()
    {
        $micro = explode(" ", microtime());
        return ((int)$micro[1]) * 1000 + ((int)round($micro[0] * 1000));
    }

    function get_random_guid()
    {
        return sprintf("%04X%04X-%04X-%04X-%04X-%04X%04X%04X", mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    function get_user_ip()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
		{
			if ($_SERVER["REMOTE_ADDR"] != $_SERVER["HTTP_CF_CONNECTING_IP"])
			{
				return $_SERVER["HTTP_CF_CONNECTING_IP"];
			}
			else
			{
				return $_SERVER["REMOTE_ADDR"];
			}
		}
		else
		{
			return $_SERVER["REMOTE_ADDR"];
		}
    }

    function _crypt($string, $mode = "encrypt")
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../data/environment/security.environment.php");

        $key = hash(SECURITY["CRYPT"]["HASHING"], SECURITY["CRYPT"]["KEY"]);

        if ($mode == "encrypt")
        {
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(SECURITY["CRYPT"]["ENCRYPTION"]));
            return base64_encode(openssl_encrypt($string, SECURITY["CRYPT"]["ENCRYPTION"], $key, 0, $iv) .":.:.:". $iv);
        }
        else if ($mode == "decrypt")
        {
            list($encrypted_string, $iv) = explode(":.:.:", base64_decode($string));

            return openssl_decrypt($encrypted_string, SECURITY["CRYPT"]["ENCRYPTION"], $key, 0, $iv);
        }

        return false;
    }

    function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
    
	function ends_with($haystack, $needle) 
	{
		return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }

    function is_base64($string)
    {
        return (bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string);
    }

    function get_server_memory_usage()
    {
        $free = (string)trim(shell_exec("free"));
    
        $mem = explode(" ", explode("\n", $free)[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
    
        return $mem[2] / $mem[1] * 100;
    }
    
    function get_server_cpu_usage()
    {
        return sys_getloadavg()[0];
    }

    function get_server_host()
    {
        $host = "http";

        if (isset($_SERVER["HTTPS"]))
        {
            $host .= "s";
        }

        return $host . "://". $_SERVER["HTTP_HOST"]; // it is not guranteed www is in the host
    }
	
	function get_version()
	{
		$version = "";
		
		$semver = @file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../packaging/version");
		$hash = @substr(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/../packaging/hash"), 0, 7);
		
		if ($semver) 
		{
			$version .= $semver;
		}		
		
		if ($hash) 
		{
			$version .= "-" .$hash ." (Docker)"; // Only hash is Docker-specific
		}
		
        if ($version)
        {
			return $version;
        } 
        else
        {
			return "Unknown";
		}
    }
	
	function get_uptime() // Unix specific
	{
		$str   = @file_get_contents("/proc/uptime");
		$num   = floatval($str);
		$secs  = fmod($num, 60); $num = intdiv($num, 60);
		$mins  = $num % 60;      $num = intdiv($num, 60);
		$hours = $num % 24;      $num = intdiv($num, 24);
		$days  = $num;
		
        return [
            $days,
            $hours,
            $mins,
            round($secs)
        ];
    }

    function console_log($string)
    {
        echo("<script type=\"text/javascript\">console.log(". $string . ");</script>");
    }
    
    function verify_captcha_response($response)
    {
        if (empty($response))
        {
            return false;
        }

        $url = "https://www.google.com/recaptcha/api/siteverify";

        $query = [ 
            "secret" => GOOGLE["RECAPTCHA"]["PRIVATE_KEY"],
            "remoteip" => get_user_ip(),
            "response" => $response
        ];

        $options = [
            "http" => [
                "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
                "method"  => "POST",
                "content" => http_build_query($query)
            ]
        ];
        $context = stream_context_create($options);
        $google_response = json_decode(file_get_contents($url, false, $context), true);

        return isset($google_response["success"]) && $google_response["success"] === true;
    }
?>