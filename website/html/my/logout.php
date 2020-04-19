<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/includes.php");

    session_clear();

    redirect("/");
?>