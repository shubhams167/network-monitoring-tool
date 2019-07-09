<?php
    session_start();
    //Check if session already exists i.e. if user is already logged in
    if(empty($_SESSION['logged_in']) || (!empty($_SESSION['logged_in']) && !$_SESSION['logged_in'])){
        $_SESSION['login_failed'] = false;
        header('Location: login.php');
        exit();//Stop executing rest script
    }
?>

<html>
    <head>
        <title>
            Monitor
        </title>
        <link rel = "stylesheet" href = "sheets/monitor-style.css" type = "text/css">
    </head>
    <body>
        <div class = "status-bar">
            <p class = "status-bar-username">
                <?php echo "Logged in as ".$_SESSION['username']."!"; ?>
            </p>
            <a class = "status-bar-logout" href = "logout.php">Logout</a>
        </div>
        <div class = "monitor-form">
            <h1>MONITOR</h1>
            <form action = "result.php" method = "POST">
                <div class = "flex-container">
                    <div class = "protocol-btn">
                        <p>Protocols</p>
                        <input type = "radio" name = "protocol" value = "tcp" checked>TCP<br>
                        <input type = "radio" name = "protocol" value = "udp">UDP<br>
                        <input type = "radio" name = "protocol" value = "arp">ARP<br>
                    </div>
                    <div class = "dropdown-menu">
                        <p>Filter</p>
                        <select name = "dropdownMenu">
                            <option value = "all packets">All packets</option>
                            <option value = "timestamp">Timestamp</option>
                        </select>
                    </div>
                </div>
                
                <input type = "submit" name = "" value = "Submit">
            </form>
        </div>
    </body>
</html>