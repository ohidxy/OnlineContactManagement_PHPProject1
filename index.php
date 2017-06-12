<?php
    session_start();

    define("DB_HOST","localhost");
    define("DB_USER","root");
    define("DB_PASS","");
    define("DB_NAME","ocm");

    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
?>

<?php
	if(isset($_POST['submit'])){              	 //checks if the submit button has been pressed
		$username = $_POST['username']; 
		$password = $_POST['password']; 
		
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
            header('Location: view_contact.php');
            exit;
        }else{
            $userFound = false;
        }
    }
    
    //showing error when user information isn't matched with database
    if(!$userFound){
        $message .= "Username or Password doesn't match!";
    }
        
			
	}else{                                           //When the submit button isn't pressed
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
		<link href="https://fonts.googleapis.com/css?family=Exo|Maven+Pro" rel="stylesheet">
		<!--//webfonts-->
</head>
<body>
<!-----start-main---->
<div class="main">

<!-- //Login Form -->
<div class="login-form">
<h1>Login To Dashboard</h1>

    <form action="index.php" method="post">

            <h2 style="font-size:25px;">USERNAME:</h2>
            <input type="text" class="text" value="<?php echo $username; ?>" name="username" >

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