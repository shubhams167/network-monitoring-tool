<?php
    session_start();
    if(!empty($_SESSION)){
        session_destroy();
    }
    else{
        header("Location: login.php");
        exit();
    }
?>

<html>
    <meta charset = "UTF-8" />
    <head>
        <title>
            Logged out
        </title>
        <link rel = "stylesheet" type = "text/css" href = "sheets/logout-style.css">
        </head>
    <body>
        <div class = "box">
            <h1>You've been logged out!</h1>
            <p>Click <a href = "login.php">here</a> to login again</p>
        </div>
    </body>
</html>