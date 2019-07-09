<?php 
    session_start();//Start a new session
    //Check if session already exists i.e. if user is already logged in
    if(!empty($_SESSION['logged_in']) && $_SESSION['logged_in']){
        header('Location: monitor.php');
        exit();//Stop executing rest script
    }

    if(!empty($_SESSION['login_failed']) && $_SESSION['login_failed']){
        echo "<script type = 'text/javascript'>alert('Login failed. Try again!');</script>";
    }
    
    //Check if form has been submitted and $_POST has form data
    if(!empty($_POST)){
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
        if($username && $password && $row[0] == $username && $row[1] == $password){
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['login_failed'] = false;
            header("Location: monitor.php");
            exit();
        }
        else{
            $_SESSION['login_failed'] = true;
            $_SESSION['logged_in'] = false;
            header('Location: login.php');
            exit();
        }

        // Free up memory
        mysqli_free_result($result);

        // Close database connection, if set
        if(isset($conn)){
            mysqli_close($conn);    
        }
    }
?>

<html>
    <meta charset = "UTF-8" />
    <head>
        <title>
            Login
        </title>
        <link rel = "stylesheet" type = "text/css" href = "sheets/login-style.css">
        </head>
    <body>
        <div class = "login-box">
            <h1>LOGIN</h1>
            <form method = "POST">
                <p>Username</p>
                <input type = "text" name = "username" placeholder = "Enter your username" required />
                <p>Password</p>
                <input type = "password" name = "password" placeholder="Enter password" required />
                <input type = "submit" name = "" value = "Login">
                <a href = "#">Forgot password?</a><br>
                <a href = "#">Sign up</a><br>
            </form>
        </div>
    </body>
</html>