<?php
    session_start();
    if(empty($_SESSION['logged_in']) || !$_SESSION['logged_in']){
        $_SESSION['login_failed'] = false;
        header('Location: login.php');
        exit();
    }
?>

<html>
    <head>
        <title>Result</title>
    </head>
    <link rel = "stylesheet" type = "text/css" href = "sheets/result-style.css">
    <body>
        <div class = "status-bar">
            <p class = "status-bar-username">
                <?php echo "Logged in as ".$_SESSION['username']."!"; ?>
            </p>
            <a class = "status-bar-logout" href = "logout.php">Logout</a>
        </div>
        <h2>Tracing packets...</h2>
        <div class = "content-box">
                <?php
                    $protocol = $_POST['protocol'];
                    $dropdownMenu = $_POST['dropdownMenu'];
                    /*
                    $output = shell_exec("./script.sh");
                    echo "<pre>".$output."</pre>";
                    */
                    $cmd = "sudo tcpdump -c 100 -nn";
                    //Decide which protocol to filter out
                    $cmd = $cmd . " " . $protocol;


                    if($dropdownMenu == "timestamp"){
                        $cmd = $cmd . " | cut -d ' ' -f 1";
                    }
                    echo "<pre>";
                    echo shell_exec($cmd);
                    echo "</pre>";
                    /*
                    //To read live output from shell
                    $proc = popen($cmd, 'r');
                    echo "<pre>";
                    while(!feof($proc)){
                        echo fread($proc, 32);//Flush output after every 32 bytes
                        @ flush();
                    }
                    echo "</pre>";
                    */
                ?>
        </div>
    </body>
    
</html>

<?php
    //session_destroy();
?>