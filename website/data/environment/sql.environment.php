<?php
    /*
        >>>> Database

        SQL["USERNAME"] => The username for the user that has *full* read/write access to the project's database.
        SQL["PASSWORD"] => The password for the database's user.
        SQL["DATABASE"] => The database's name.
        SQL["HOSTNAME"] => The IP address where the database is hosted.
        SQL["PORT"] => The port of the IP in which the database is hosted.
    */

    define("SQL", [
        "USERNAME" => "root",
        "PASSWORD" => "sample",
        "DATABASE" => "rboxlo",
        "HOST"     => "database",
        "PORT"     => "3306"
    ]);
?>