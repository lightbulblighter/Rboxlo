<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/rbx.php");
    header("Content-Type: text/plain");

    if ($_SERVER["HTTP_USER_AGENT"] !== "Roblox/WinInet")
    {
        exit("Not being called from Roblox client");
    }
    
    $ticket = $_SERVER["HTTP_ROBLOX-SESSION-ID"] . ";" . $_SERVER["HTTP_ROBLOX-GAME-ID"] . ";" . date("n/j/Y g:i:s A");
    $result = "<Value Type=\"string\">". get_signature($ticket) . "</Value>";

    exit($result);
?>