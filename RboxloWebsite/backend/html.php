<?php
    function build_js()
    {
        include_once(BASE_PATH ."/backend/components/javascript.php");
    }

    function build_navigation_bar()
    {
        include_once(BASE_PATH ."/backend/components/navigation_bar.php");
        include_once(BASE_PATH ."/backend/components/news.php");
    }

    function build_header($page_name = "")
    {
        include_once(BASE_PATH ."/backend/components/header.php");
    }

    function build_footer()
    {
        include_once(BASE_PATH ."/backend/components/footer.php");
    }
?>