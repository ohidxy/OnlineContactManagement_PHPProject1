<?php
    ob_start();
    session_start();
?>

<?php
    require("includes/connect.php");   //Database connection
    require("includes/throttle.php");   //Database connection
    require_once("includes/Token.php");   //CSRF token
?>

<?php
if(isset($_POST['submit'])){              	 //checks if the submit button has been pressed
    if(Token::checkToken($_POST['csrf_login'])){
		$username = $mysqli->real_escape_string($_POST['username']);
        $username = trim($username, " ");
        
		$password = $mysqli->real_escape_string($_POST['password']); 
        
		$message="";      //defining error message 
		
		//Checking whether value is not set or empty
		if(!isset($username)||empty($username))
			$message .= "Please, Enter Your Username!<br/>";
		else if(!preg_match("/@/",$username))     					//Username Format Check
			$message .= "Please, Use Your Email to Log in!<br/>";

			
			
		$valueLength = strlen($password);
		$max = 25;               //Maximum Password Length
		$min = 3;				//Minimum Password Length
		
		if(empty($password))         												 //Check if empty or not set
			$message .= "Please, Enter Your Password!<br/>";
		else if($valueLength<$min || $valueLength>$max)							  //Minimum and Maximum Password Length check 
			$message .="Please, Choose a password between 3 to 25 Characters Length!<br/>";
        
        //Query for selecting email from tabe "user"
        $_sql = "SELECT full_name, email, password FROM user";
        $result = $mysqli->query($_sql);
        
    
    while($row = $result->fetch_assoc()){
        if($row["email"] === $username && password_verify($password,$row["password"])){
            $message .= "Logging Success!";
            
            $userFound = true;
            $_SESSION["email"]=$row["email"];
            $_SESSION["fullname"] = $row["full_name"];
            $_SESSION["failed_login_count"] = $row["failed_login_count"];
            
            header("Location:view_contact.php");
            exit;
        }
    }
    
    //showing error when user information isn't matched with database
    if(!isset($userFound)){
        $message .= "Username or Password doesn't match!";
    }
        
			
	}
}
else{                                           //When the submit button isn't pressed
		$username = null;
		$password = null;
		$message = null;
}
?>



<!DOCTYPE html>
<html>
<head>
	<title>Online Contact Management</title>
		<meta charset="utf-8">
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<!--webfonts-->
		<link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
		<!--//webfonts-->
</head>
<body>
<!-----start-main---->
<div class="main">

<!-- //Login Form -->
<div class="login-form">
<h1>Login To Dashboard</h1>
    <p>Demo Account: <br>[ <b>Username:</b> admin@ohid.info <b>Password:</b> ocm123 ]</p>
    <form action="index.php" method="post">
            <input type="hidden" name="csrf_login" value="<?php echo Token::generateToken(); ?>">
            <h2 style="font-size:25px;">USERNAME:</h2>
            <input type="email" class="text" value="<?php echo $username; ?>" name="username" >

            <h2 style="font-size:25px;">PASSWORD:</h2>
            <input type="password" value="" name="password" >

            <p style="color:red;"><b><?php echo $message; ?></b></p>	<!--All Common Error Message Here -->
            <br/>

            <div class="submit"> 
                <input type="submit" value="LOGIN" name="submit">
            </div>	



            <p><a href="#">Forgot Password ?</a></p>
            <br/>
        <!--Creating a new account button -->
        <a href="signup.php">
            <input type="button" value="SIGN UP" >
        </a>

    </form>
</div>
<!--//End-login-form-->


<!-----start-copyright---->
<div class="copy-right">
    <p>Copyright By <a href="http://ohid.info">Ohid.info</a></p> 
</div>
<!-----//end-copyright---->
</div>
<br/><br/><br/>
<!-----//end-main---->
		 		
</body>
</html>