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
        $key = hash(ENVIRONMENT["SECURITY"]["CRYPT"]["HASH"], ENVIRONMENT["SECURITY"]["CRYPT"]["KEY"]);

        if ($mode == "encrypt")
        {
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENVIRONMENT["SECURITY"]["CRYPT"]["ENCRYPTION"]));
            return base64_encode(openssl_encrypt($string, ENVIRONMENT["SECURITY"]["CRYPT"]["ENCRYPTION"], $key, 0, $iv) .":.:.:". $iv);
        }
        else if ($mode == "decrypt")
        {
            list($encrypted_string, $iv) = explode(":.:.:", base64_decode($string));

            return openssl_decrypt($encrypted_string, ENVIRONMENT["SECURITY"]["CRYPT"]["ENCRYPTION"], $key, 0, $iv);
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

        return $host . "://". $_SERVER["HTTP_HOST"];
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
	
	function get_uptime() // Unix specific
	{
		$str   = @file_get_contents("/proc/uptime");
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

    function console_log($string)
    {
        return "console.log({$string});";
    }

    // Hacky implementation of https://github.com/albertcht/invisible-recaptcha, without the fluff
    function send_verify_request_captcha(array $query = [])
    {
        $url = "https://www.google.com/recaptcha/api/siteverify";

        $options = [
            "http" => [
                "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
                "method"  => "POST",
                "content" => http_build_query($query)
            ]
        ];
        
        $context = stream_context_create($options);

        return json_decode(file_get_contents($url, false, $context), true);
    }
    
    function verify_response_captcha($response, $ip)
    {
        if (empty($response))
        {
            return false;
        }

        $response = send_verify_request_captcha([
            "secret" => ENVIRONMENT["GOOGLE"]["RECAPTCHA"]["PRIVATE_KEY"],
            "remoteip" => $ip,
            "response" => $response
        ]);

        return isset($response["success"]) && $response["success"] === true;
    }

    function render_polyfill_captcha()
    {
        return "<script src=\"https://cdn.polyfill.io/v2/polyfill.min.js\"></script>" . PHP_EOL;
    }

    function render_html_captcha()
    {
        $html = "<div id=\"_g-recaptcha\"></div>" . PHP_EOL;
        if (ENVIRONMENT["GOOGLE"]["RECAPTCHA"]["BADGE_HIDE"])
        {
            $html .= "<style>.grecaptcha-badge{display:none;!important}</style>" . PHP_EOL;
        }

        $html .= "<div class=\"g-recaptcha\" data-sitekey=\"" . ENVIRONMENT["GOOGLE"]["RECAPTCHA"]["PUBLIC_KEY"] ."\"";
        $html .= "data-size=\"invisible\" data-callback=\"_submitForm\" data-badge=\"" . ENVIRONMENT["GOOGLE"]["RECAPTCHA"]["BADGE_POSITION"] . "\"></div>";
        return $html;
    }

    function render_debug_captcha()
    {
        $elements = [
            "_submitForm",
            "_captchaForm",
            "_captchaSubmit"
        ];

        $html = "";

        foreach ($elements as $element)
        {
            $html .= console_log("\"Checking element binding of " . $element . "...\"");
            $html .= console_log($element . "!==undefined");
        }

        return $html;
    }

    function render_footer_js_captcha()
    {
        // TODO: Possible multilingual support? api.js?hl=lang. perhaps add it when site translations are available

        $html = "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>" . PHP_EOL;
        $html .= "<script>var _submitForm,_captchaForm,_captchaSubmit,_execute=true;</script>";
        $html .= "<script>window.addEventListener('load', _loadCaptcha);" . PHP_EOL;

        $html .= "function _loadCaptcha(){";
        if (ENVIRONMENT["GOOGLE"]["RECAPTCHA"]["BADGE_HIDE"])
        {
            $html .= "document.querySelector(\".grecaptcha-badge\").style = \"display:none;!important\"" . PHP_EOL;
        }

        $html .= "_captchaForm=document.querySelector(\"#_g-recaptcha\").closest(\"form\");";
        $html .= "_captchaSubmit=_captchaForm.querySelector(\"[type=submit]\");";
        $html .= "_submitForm=function(){if(typeof _submitEvent===\"function\"){_submitEvent();";
        $html .= "grecaptcha.reset();}else{_captchaForm.submit();}};";
        $html .= "_captchaForm.addEventListener(\"submit\",";
        $html .= "function(e){e.preventDefault();if(typeof _beforeSubmit===\"function\"){";
        $html .= "_execute=_beforeSubmit(e);}if(_execute){grecaptcha.execute();}});";
        if (ENVIRONMENT["GOOGLE"]["RECAPTCHA"]["DEBUG"])
        {
            $html .= render_debug_captcha();
        }
        $html .= "}</script>" . PHP_EOL;

        return $html;
    }
    
    function render_captcha()
    {
        $html = render_polyfill_captcha();
        $html .= render_html_captcha();
        $html .= render_footer_js_captcha();

        return $html;
    }
?>