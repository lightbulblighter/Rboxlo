<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");

    header("Content-Type: text/plain");
    
    $ut = get_uptime();
    
    exit(json_encode([
        "time" => time(),
        "cpu" => round(get_server_cpu_usage()),
        "ram" => round(get_server_memory_usage()),
        "uptime" => "$ut[0] days, $ut[1] hours, $ut[2] minutes, and $ut[3] seconds"
    ], JSON_PRETTY_PRINT));
?>