<?php
    //***************************************//
    //********* BEGIN PHP FUNCTIONS *********//
    //***************************************//

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
        header("Location: ". $location);
        exit();
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
        $key = hash(CRYPT_HASH_ALGO, CRYPT_KEY);

        if ($mode == "encrypt")
        {
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(CRYPT_ENCRYPTION));
            return base64_encode(openssl_encrypt($string, CRYPT_ENCRYPTION, $key, 0, $iv) .":.:.:". $iv);
        }
        else if ($mode == "decrypt")
        {
            list($encrypted_string, $iv) = explode(":.:.:", base64_decode($string));

            return openssl_decrypt($encrypted_string, CRYPT_ENCRYPTION, $key, 0, $iv);
        }

        return false;
    }

    //*************************************//
    //********* END PHP FUNCTIONS *********//
    //*************************************//
    

    //******************************************//
    //********* BEGIN STRING FUNCTIONS *********//
    //******************************************//

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

    //****************************************//
    //********* END STRING FUNCTIONS *********//
    //****************************************//
    

    //******************************************//
    //********* BEGIN SYSTEM FUNCTIONS *********//
    //******************************************//
    
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
	
	function get_version() // Only hash is Docker-specific
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
			$version .= "-" .$hash ." (Docker)";
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
	
	function get_uptime() // Linux specific
	{
		$str   = @file_get_contents('/proc/uptime');
		$num   = floatval($str);
		$secs  = fmod($num, 60); $num = intdiv($num, 60);
		$mins  = $num % 60;      $num = intdiv($num, 60);
		$hours = $num % 24;      $num = intdiv($num, 24);
		$days  = $num;
		
        return array(
            $days,
            $hours,
            $mins,
            round($secs)
        );
    }
    
    //****************************************//
    //********* END SYSTEM FUNCTIONS *********//
    //****************************************//
?>