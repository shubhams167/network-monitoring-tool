<?php 
    session_start();//Start a new session
    //Check if session already exists i.e. if user is already logged in
    if(!empty($_SESSION['logged_in']) && $_SESSION['logged_in']){
        header('Location: monitor.php');
        exit();//Stop executing rest script
    }

    //Check if form has been submitted and $_POST has form data
    if(isset($_POST['submit'])){
	//Google recaptcha
	$secretKey  = "6Lef_rkUAAAAAOyX6pK2oe3r58SVY_WqYixH2frl";
	$responseKey = $_POST['g-recaptcha-response'];
	$userIP = $_SERVER['REMOTE_ADDR'];
	$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
	$response = file_get_contents($url);
	$response = json_decode($response);
	if($response->success){
	        // Connect to Database
	        $dbHost = "nmt-test.cz8m7me2fz6g.ap-south-1.rds.amazonaws.com:3306";
	        $dbUser = "admin";
	        $dbPass = "BigSniper!";
	        $dbName = "nmt";
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
		    echo "<script> window.onload = function () { document.getElementById('error').innerHTML = 'Incorrect username or password';} </script>";
        	}

	        // Free up memory
        	mysqli_free_result($result);

	        // Close database connection, if set
        	if(isset($conn)){
	            mysqli_close($conn);    
        	}
	}
	else{
		echo "<script> window.onload = function () { document.getElementById('error').innerHTML = 'Captcha verification failed';} </script>";
		//unset($_POST['submit']);
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
	    <h6 id = "error"></h6>
            <form method = "POST" autocomplete = "off">
                <p>Username</p>
                <input type = "text" name = "username" placeholder = "Enter your username" required />
                <p>Password</p>
                <input type = "password" name = "password" placeholder="Enter password" required />
		<div class = "g-recaptcha" data-sitekey = "6Lef_rkUAAAAAMF30psvl82uGeW28lepSQdHLD20"></div>
                <input type = "submit" name = "submit" value = "Login">
                <a onclick = "showMsg()">Forgot password?</a><br>
                <a onclick = "showMsg()">Sign up</a><br>
                <script>
                    function showMsg(){
                        alert("Please contact admin.");
                    }
                </script>
            </form>
	<script src = "https://www.google.com/recaptcha/api.js"></script>
        </div>
    </body>
    <footer>
        Developed by Shubham Singh
    </footer>
</html>
