<?php
    header("Content-Type: application/json");

    // no moderation here :D

    exit(json_encode([
        "data" => [
            "white" => $_POST["text"],
            "black" => $_POST["text"]
        ]
    ]));
?>