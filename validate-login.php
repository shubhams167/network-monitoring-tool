<?php 
    // Connect to Database
    $dbHost = "localhost";
    $dbUser = "BigSniper";
    $dbPass = "1998";
    $dbName = "NetworkMonitoringTool";
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

    // Test if connection occurred
    if(mysqli_errno($conn)){
        die("Database connection failed: " . 
           mysqli_connect_error() . 
           " (" . mysqli_connect_errno() . ")"
           );
    }

    // Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query database
    $query = "SELECT * FROM credentials WHERE username = '$username' AND password = '$password' ";
    $result = mysqli_query($conn, $query) or die("Database query failed");
    

    // Process the returned data
    $row = mysqli_fetch_row($result);
    if($row[0] == $username && $row[1] == $password && $username != '' && $password != ''){
        echo "Login successful";
    }
    else{
        echo "Login failed. Try again!";
        //Reload login page to ask again for username of password
        include('index.html');
    }

    // Free up memory
    mysqli_free_result($result);
    
    // Close database connection, if set
    if(isset($conn)){
        mysqli_close($conn);    
    }
?>
