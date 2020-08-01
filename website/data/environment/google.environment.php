<?php
    /* 
        >>> Google

        =========================>
        ==> NOTE: Rboxlo currently uses Invisible reCAPTCHA (v2.)
        ==> Make sure that the reCAPTCHA keys are Invisible reCAPTCHA v2 keys.
        =========================>

        GOOGLE["ANALYTICS"]["ENABLED"] => Whether to use Google Analytics or not.
        GOOGLE["ANALYTICS"]["TAG"] => The Google Analytics tag for this website. Leave it blank if you don't want analytics.
        GOOGLE["RECAPTCHA"]["PUBLIC_KEY"] => (AKA SITE KEY) The reCAPTCHA public key. This can be obtained from the reCAPTCHA admin console.
        GOOGLE["RECAPTCHA"]["PRIVATE_KEY"] => (AKA SECRET KEY) The reCAPTCHA private key. This can be obtained from the reCAPTCHA admin console.
    */
    
    define("GOOGLE", [ 
        "ANALYTICS" => [
            "ENABLED" => false,
            "TAG" => ""
        ],
        "RECAPTCHA" => [
            "PUBLIC_KEY" => "",
            "PRIVATE_KEY" => ""
        ]
    ]);
?>