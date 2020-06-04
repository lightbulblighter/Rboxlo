<?php
    function build_js()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/components/javascript.php");
    }

    function build_navigation_bar()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/components/navigation_bar.php");
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/components/news.php");
    }

    function build_header($page_name = "")
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/components/header.php");
    }

    function build_footer()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/components/footer.php");
    }
?>