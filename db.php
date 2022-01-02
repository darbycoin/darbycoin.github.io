 <?php
    session_start();

    $dbPass = 'yRpUd6Dh0f5qNngi';
    $dbName = 'quiz1auctions';
    $dbUser = 'quiz1auctions';
    $dbHost = 'localhost:3333';


    $link = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    if (!$link) {
        die("Fatal error: Failed to connect to mySQL -" . mysqli_connect_error());
    }
