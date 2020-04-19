<?php
    function build_js()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/components/javascript.php");
    }

    function build_navigation_bar()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/components/navigation_bar.php");
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/components/news.php");
    }

    function build_header($page_name = "")
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/components/header.php");
    }

    function build_footer()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/components/footer.php");
    }
?>