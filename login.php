<?php 
    session_start();//Start a new session
    //Check if session already exists i.e. if user is already logged in
    if(!empty($_SESSION['logged_in']) && $_SESSION['logged_in']){
        header('Location: monitor.php');
        exit();//Stop executing rest script
    }

    /*
    if(!empty($_SESSION['login_failed']) && $_SESSION['login_failed']){
        echo "<script type = 'text/javascript'>alert('Login failed. Try again!');</script>";
    }*/
    
	// Google reCaptcha secret key
	$secretKey  = "9Seh-ksJHSUysHGST8y4KSg0UisYS7A3QUjsGHSt";
	$captcha = 'failed';
	$statusMsg = '';
	if(isset($_POST['submit'])){
	    if(isset($_POST['captcha-response']) && !empty($_POST['captcha-response'])){
	        // Get verify response data
	        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['captcha-response']);
	        $responseData = json_decode($verifyResponse);
	        if($responseData->success){
	            //Contact form submission code goes here ...
	  		$captcha = 'success';
	            $statusMsg = 'Your contact request have submitted successfully.';
	        }else{
	            $statusMsg = 'Robot verification failed, please try again.';
	        }
	    }else{
        	$statusMsg = 'Robot verification failed, please try again.';
		}
	}
	
    //Check if form has been submitted and $_POST has form data
    if(!empty($_POST) && $captcha == 'success'){
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
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
	<script>
		var onloadCallback = function() {
		    grecaptcha.execute();
		};

		function setResponse(response) { 
		    document.getElementById('captcha-response').value = response; 
		}
	</script>	
    </head>
    <body>
        <div class = "login-box">
            <h1>LOGIN</h1>
            <form method = "POST" autocomplete = "off">
                <p>Username</p>
                <input type = "text" name = "username" placeholder = "Enter your username" required />
                <p>Password</p>
                <input type = "password" name = "password" placeholder="Enter password" required />
		<div class = "g-recaptcha" data-sitekey = "6LcS9bkUAAAAAEwFY4lFpxaMw8iLhkCTDRC56R-J" data-badge = "inline" data-size = "invisible" data-callback = "setResponse"></div>
		<input type = "hidden" id = "captcha-response" name = "captcha-response" />
                <input type = "submit" name = "" value = "Login">
                <a onclick = "showMsg()">Forgot password?</a><br>
                <a onclick = "showMsg()">Sign up</a><br>
                <script>
                    function showMsg(){
                        alert("Please contact admin.");
                    }
                </script>
            </form>
        </div>
    </body>
    <footer>
        Developed by Shubham Singh
    </footer>
</html>
