<?php
    require_once("/var/www/backend/includes.php");

    exit(json_encode([
        "time" => time(),
        "cpu" => get_server_cpu_usage(),
        "ram" => get_server_memory_usage()
    ]))
?>