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
                <?php echo "Logged in as <u>".$_SESSION['username']."</u>"; ?>
            </p>
            <a class = "status-bar-logout" href = "logout.php">Logout</a>
        </div>
        <?php
            $protocol = $_POST['protocol'];
            echo "<h2>" . strtoupper($protocol) . " packets</h2>";
        ?>
        <div class = "content-box">
            <table>
                <?php
                    $filter = $_POST['filter'];
                    $packetCount = $_POST['packetCount'];
                
                    //Command to read packets and store them in a .pcap file
                    $cmd = "sudo tcpdump -i enp0s3 -w output.pcap";
                    //Add packet count
                    $cmd = $cmd . " -c " . $packetCount;
                    //Decide which protocol to filter out
                    $cmd = $cmd . " " . $protocol;
                    //Execute command on shell
                    echo shell_exec($cmd);
                
                    //Update command to read from output file
                    $cmd = "sudo tcpdump -r output.pcap -nn";

                    //Table header
                    echo "<tr>";
                    if($filter == "timestamp" || $filter == "all"){
                        echo "<th>Timestamp</th>";
                    }
                    if($filter == "sourceIP" || $filter == "all"){
                        echo "<th>Source IPv4</th>";
                    }
                    if($filter == "sourcePort" || $filter == "all"){
                        echo "<th>Source Port</th>";
                    }
                    if($filter == "destinationIP" || $filter == "all"){
                        echo "<th>Destination IPv4</th>";
                    }
                    if($filter == "destinationPort" || $filter == "all"){
                        echo "<th>Destination Port</th>";
                    }
                    if($filter == "sourceMAC" || $filter == "all"){
                        echo "<th>Source MAC</th>";
                    }
                    if($filter == "destinationMAC" || $filter == "all"){
                        echo "<th>Destination MAC</th>";
                    }
                    if($filter == "packetLength" || $filter == "all"){
                        echo "<th>Packet length</th>";
                    }
                    echo "</tr>";
                
                    echo "<tr>";
                    //Commands specific for TCP packets
                    if($protocol == "tcp"){
                        if($filter == "timestamp" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | cut -d ' ' -f 1");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "sourceIP" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | cut -d ' ' -f 3 | cut -d '.' -f 1,2,3,4");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "sourcePort" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | cut -d ' ' -f 3 | cut -d '.' -f 5");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "destinationIP" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | cut -d ' ' -f 5 | cut -d '.' -f 1,2,3,4");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "destinationPort" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | cut -d ' ' -f 5 | cut -d '.' -f 5 | cut -d ':' -f 1");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "sourceMAC" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " -e | cut -d ' ' -f 2");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "destinationMAC" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " -e | cut -d ' ' -f 4 | cut -d ',' -f 1");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "packetLength" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | awk '{print $(NF)}'");
                            echo "</td>";
                            echo "</pre>";
                        }
//                        if($filter == "all"){
//                            echo "<td style = 'overflow-x = scroll;'>";
//                            echo "<pre>";
//                            echo shell_exec($cmd . " | grep -v 'IP6' | cut -d ' ' -f 6- | rev | cut -d ' ' -f 1,2 --complement | cut -c 1 --complement | rev");
//                            echo "</td>";
//                            echo "</pre>";
//                        }
                        //exec($cmd . " | grep -v 'IP6' | cut -d ' ' -f 6- | rev | cut -d ' ' -f 1,2 --complement | cut -c 1 --complement | rev", $outp, $ret);
                        //echo sizeof($outp);
                        //echo $outp[1];
                        
                    }
                    //Commands specific for UDP packets
                    else if($protocol == "udp"){
                        if($filter == "timestamp" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | grep 'UDP' |grep -v 'NBT' | cut -d ' ' -f 1");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "sourceIP" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 3 | cut -d '.' -f 1,2,3,4");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "sourcePort" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 3 | cut -d '.' -f 5");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "destinationIP" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 5 | cut -d '.' -f 1,2,3,4");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "destinationPort" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 5 | cut -d '.' -f 5 | cut -d ':' -f 1");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "sourceMAC" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " -e | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 2");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "destinationMAC" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " -e | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 4 | cut -d ',' -f 1");
                            echo "</td>";
                            echo "</pre>";
                        }
                        if($filter == "packetLength" || $filter == "all"){
                            echo "<td>";
                            echo "<pre>";
                            echo shell_exec($cmd . " | grep 'UDP' | grep -v 'NBT' | awk '{print $(NF)}'");
                            echo "</td>";
                            echo "</pre>";
                        }
                    }
                    //Commands specific for ARP packets
                    else
                    echo "<tr>";

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
            </table>
            <p><u>Note</u>:- Some packets might be missing due to insufficient data</p>
        </div>
    </body>
    <footer>
        Developed by Shubham Singh
    </footer>
</html>