<html>
    <head>
        <title>Result</title>
    </head>
    <link rel = "stylesheet" type = "text/css" href = "result-style.css">
    <body>
        <h1>RESULT</h1>
        <?php
            $protocol = $_POST['protocol'];
            $dropdownMenu = $_POST['dropdownMenu'];
            /*
            $output = shell_exec("./script.sh");
            echo "<pre>".$output."</pre>";
            */
            $cmd = "sudo tcpdump -c 100 -nn";
            //Decide which protocol to filter out
            if($protocol == "tcp"){
                $cmd = $cmd . " tcp";
            }
            else if($protocol == "udp"){
                $cmd = $cmd . " udp";
            }
            else{
                $cmd = $cmd . " arp";
            }

            
            if($dropdownMenu == "timestamp"){
                $cmd = $cmd . " | cut -d ' ' -f 1";
            }
            
            echo "<div class = 'content-box'>";
            echo "<pre>";
            echo shell_exec($cmd);
            echo "</pre>";
            echo "</div>";

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
    </body>
    
</html>