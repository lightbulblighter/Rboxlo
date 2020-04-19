<?php
    function build_js()
    {
        include_once(file_build_path("backend", "components", "javascript.php"));
    }

    function build_navigation_bar()
    {
        include_once(file_build_path("backend", "components", "navigation_bar.php"));
        include_once(file_build_path("backend", "components", "news.php"));
    }

    function build_header($page_name = "")
    {
        include_once(file_build_path("backend", "components", "header.php"));
    }

    function build_footer()
    {
        include_once(file_build_path("backend", "components", "footer.php"));
    }
?>