<?php
    header("Content-Type: text/plain");

    if (!isset($_GET["id"]) || strlen($_GET["id"]) <= 0 || !is_int($_GET["id"]))
    {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    // Redirect to /asset/?id=%d as it's the newer API.
    redirect("/asset/?id=". $_GET["id"]);
?>