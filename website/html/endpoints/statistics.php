<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");

    exit(json_encode([
        "time" => time(),
        "cpu" => get_server_cpu_usage(),
        "ram" => round(get_server_memory_usage())
    ]))
?>