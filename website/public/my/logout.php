<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");

    session_clear();

    redirect("/");
?>