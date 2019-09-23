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
                <?php
                    $filter = $_POST['filter'];
                    $packetCount = $_POST['packetCount'];
                    if($filter == "all")
                        echo "<table style = 'width: 100%;'>";
                    else
                        echo "<table>";
                    
                    //Command to read packets and store them in a .pcap file
                    $cmd = "sudo tcpdump -i eth0 -w output.pcap";
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
                    if($filter == "all"){
                        echo "<th>Info</th>";
                    }
                    echo "</tr>";
                
                    //Commands specific for TCP packets
                    if($protocol == "tcp"){
                        exec($cmd . " | cut -d ' ' -f 1", $timestamp, $returnVal);
                        exec($cmd . " | cut -d ' ' -f 3 | cut -d '.' -f 1,2,3,4", $sourceIP, $returnVal);
                        exec($cmd . " | cut -d ' ' -f 3 | cut -d '.' -f 5", $sourcePort, $returnVal);
                        exec($cmd . " | cut -d ' ' -f 5 | cut -d '.' -f 1,2,3,4", $destinationIP, $returnVal);
                        exec($cmd . " | cut -d ' ' -f 5 | cut -d '.' -f 5 | cut -d ':' -f 1", $destinationPort, $returnVal);
                        exec($cmd . " -e | cut -d ' ' -f 2", $sourceMAC, $returnVal);
                        exec($cmd . " -e | cut -d ' ' -f 4 | cut -d ',' -f 1", $destinationMAC, $returnVal);
                        exec($cmd . " | awk '{print $(NF)}'", $packetLength, $returnVal);
                        exec($cmd . " | grep -v 'IP6' | cut -d ' ' -f 6- | rev | cut -d ' ' -f 1,2 --complement | cut -c 1 --complement | rev", $info, $returnVal);
                        for($i = 0; $i < sizeof($timestamp); $i += 1){
                            echo "<tr>";
                            if($filter == "timestamp" || $filter == "all"){
                                echo "<td>".$timestamp[$i]."\n</td>";
                            }
                            if($filter == "sourceIP" || $filter == "all"){
                                echo "<td>".$sourceIP[$i]."\n</td>";
                            }
                            if($filter == "sourcePort" || $filter == "all"){
                                echo "<td>".$sourcePort[$i]."\n</td>";
                            }
                            if($filter == "destinationIP" || $filter == "all"){
                                echo "<td>".$destinationIP[$i]."\n</td>";
                            }
                            if($filter == "destinationPort" || $filter == "all"){
                                echo "<td>".$destinationPort[$i]."\n</td>";
                            }
                            if($filter == "sourceMAC" || $filter == "all"){
                                echo "<td>".$sourceMAC[$i]."\n</td>";
                            }
                            if($filter == "destinationMAC" || $filter == "all"){
                                echo "<td>".$destinationMAC[$i]."\n</td>";
                            }
                            if($filter == "packetLength" || $filter == "all"){
                                echo "<td>".$packetLength[$i]."\n</td>";
                            }
                            if($filter == "all"){
                                echo "<td>".$info[$i]."\n</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    //Commands specific for UDP packets
                    else if($protocol == "udp"){
                        exec($cmd . " | grep 'UDP' |grep -v 'NBT' | cut -d ' ' -f 1", $timestamp, $returnVal);
                        exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 3 | cut -d '.' -f 1,2,3,4", $sourceIP, $returnVal);
                        exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 3 | cut -d '.' -f 5", $sourcePort, $returnVal);
                        exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 5 | cut -d '.' -f 1,2,3,4", $destinationIP, $returnVal);
                        exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 5 | cut -d '.' -f 5 | cut -d ':' -f 1", $destinationPort, $returnVal);
                        exec($cmd . " -e | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 2", $sourceMAC, $returnVal);
                        exec($cmd . " -e | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 4 | cut -d ',' -f 1", $destinationMAC, $returnVal);
                        exec($cmd . " | grep 'UDP' | grep -v 'NBT' | awk '{print $(NF)}'", $packetLength, $returnVal);
                        exec($cmd . " | grep 'UDP' | grep -v 'NBT' | cut -d ' ' -f 6- | rev | cut -d ' ' -f 1,2 --complement | cut -c 1 --complement | rev", $info, $returnVal);
                        for($i = 0; $i < sizeof($timestamp); $i += 1){
                            echo "<tr>";
                            if($filter == "timestamp" || $filter == "all"){
                                echo "<td>".$timestamp[$i]."\n</td>";
                            }
                            if($filter == "sourceIP" || $filter == "all"){
                                echo "<td>".$sourceIP[$i]."\n</td>";
                            }
                            if($filter == "sourcePort" || $filter == "all"){
                                echo "<td>".$sourcePort[$i]."\n</td>";
                            }
                            if($filter == "destinationIP" || $filter == "all"){
                                echo "<td>".$destinationIP[$i]."\n</td>";
                            }
                            if($filter == "destinationPort" || $filter == "all"){
                                echo "<td>".$destinationPort[$i]."\n</td>";
                            }
                            if($filter == "sourceMAC" || $filter == "all"){
                                echo "<td>".$sourceMAC[$i]."\n</td>";
                            }
                            if($filter == "destinationMAC" || $filter == "all"){
                                echo "<td>".$destinationMAC[$i]."\n</td>";
                            }
                            if($filter == "packetLength" || $filter == "all"){
                                echo "<td>".$packetLength[$i]."\n</td>";
                            }
                            if($filter == "all"){
                                echo "<td>".$info[$i]."\n</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    //Commands specific for ARP packets
                    else{
                        exec($cmd . " | cut -d ' ' -f 1", $timestamp, $returnVal);
                        //exec($cmd . " | cut -d ' ' -f 3 | cut -d '.' -f 1,2,3,4", $sourceIP, $returnVal);
                        //exec($cmd . " | cut -d ' ' -f 3 | cut -d '.' -f 5", $sourcePort, $returnVal);
                        //exec($cmd . " | cut -d ' ' -f 5 | cut -d '.' -f 1,2,3,4", $destinationIP, $returnVal);
                        //exec($cmd . " | cut -d ' ' -f 5 | cut -d '.' -f 5 | cut -d ':' -f 1", $destinationPort, $returnVal);
                        exec($cmd . " -e | cut -d ' ' -f 2", $sourceMAC, $returnVal);
                        exec($cmd . " -e | cut -d ' ' -f 4 | cut -d ',' -f 1", $destinationMAC, $returnVal);
                        exec($cmd . " | awk '{print $(NF)}'", $packetLength, $returnVal);
                        exec($cmd . " | cut -d ',' -f 2 | cut -c 1 --complement", $info, $returnVal);
                        for($i = 0; $i < sizeof($timestamp); $i += 1){
                            echo "<tr>";
                            if($filter == "timestamp" || $filter == "all"){
                                echo "<td>".$timestamp[$i]."\n</td>";
                            }
                            if($filter == "sourceIP" || $filter == "all"){
                                echo "<td>"."NA"."\n</td>";
                            }
                            if($filter == "sourcePort" || $filter == "all"){
                                echo "<td>"."NA"."\n</td>";
                            }
                            if($filter == "destinationIP" || $filter == "all"){
                                echo "<td>"."NA"."\n</td>";
                            }
                            if($filter == "destinationPort" || $filter == "all"){
                                echo "<td>"."NA"."\n</td>";
                            }
                            if($filter == "sourceMAC" || $filter == "all"){
                                echo "<td>".$sourceMAC[$i]."\n</td>";
                            }
                            if($filter == "destinationMAC" || $filter == "all"){
                                echo "<td>".$destinationMAC[$i]."\n</td>";
                            }
                            if($filter == "packetLength" || $filter == "all"){
                                echo "<td>".$packetLength[$i]."\n</td>";
                            }
                            if($filter == "all"){
                                echo "<td>".$info[$i]."\n</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                ?>
            <p><u>Note</u>:- Unsupported packets have been dropped</p>
        </div>
    </body>
    <footer>
        Developed by Shubham Singh
    </footer>
</html>
