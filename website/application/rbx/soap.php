<?php
    function soap_get_envelope($action, $content)
    {
        $envelope = "";

        $envelope .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $envelope .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:ns2="http://roblox.com/RCCServiceSoap" xmlns:ns1="http://roblox.com/" xmlns:ns3="http://roblox.com/RCCServiceSoap12">' . "\n";
        $envelope .= '<SOAP-ENV:Body SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' . "\n";

        $envelope .= "<ns1:". $action . ">\n";

        foreach ($content as $message)
        {
            $envelope .= "<ns1:". $message["submethod"] . ">\n";
            
            if (isset($message["details"]))
            {
                foreach ($message["details"] as $detail)
                {
                    $envelope .= "<ns1:". array_search($detail, $message["details"]) .">$detail</ns1:" . array_search($detail, $message["details"]) .">\n";
                }
            }
            else
            {
                $envelope .= $message["content"];
            }

            $envelope .= "</ns1:". $message["submethod"] . ">\n";
        }

        $envelope .= "</ns1:". $action . ">\n";

        $envelope .= "</SOAP-ENV:Body>\n";
        $envelope .= "</SOAP-ENV:Envelope>\n";
        
        return $envelope;
    }

    function soap_send_envelope($ip, $port, $action, $envelope)
    {
        // cURL is used for this function instead of file_get_contents because it's better and versatile.

        // Determine key variables before sending the message
        $header = [
            "Content-Type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPACTION: $action",
            "Content-Length: ". strlen($envelope)
        ];

        $ip .= ":$port";
        
        // Setup cURL
        $message = curl_init();
        
        curl_setopt($message, CURLOPT_URL,            $ip  );
        curl_setopt($message, CURLOPT_CONNECTTIMEOUT, 10   );
        curl_setopt($message, CURLOPT_TIMEOUT,        10   );
        curl_setopt($message, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($message, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($message, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($message, CURLOPT_POST,           true );
        curl_setopt($message, CURLOPT_POSTFIELDS,     $envelope);
        curl_setopt($message, CURLOPT_HTTPHEADER,     $header);

        if (curl_exec($message) === false)
        {
            curl_close($message);
            return false;
        }
        else
        {
            curl_close($message);
            return true;
        }

        return false;
    }
?>