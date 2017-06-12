<?php 
    session_start();
    
    $_SESSION["message"]="Welcome";

    define("DB_HOST","localhost");
    define("DB_USER","root");
    define("DB_PASS","");
    define("DB_NAME","ocm");

    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
?>

<!-- Form validation through PHP -->
<?php
	if(isset($_POST['submit'])){              	 //checks if the submit button has been pressed
		$fullname = $mysqli->real_escape_string($_POST['fullname']); 
		$email = $mysqli->real_escape_string($_POST['email']); 
		$password = $mysqli->real_escape_string($_POST['password']); //md5 hash password
        
		
		$message="";      //defining error message 
		
		$loginAble = true;   // a boolean checks all the validation to sign up
		if(!isset($fullname)||empty($fullname)){
            $message .= "Please, Enter Your Name!<br/>";
            $loginAble = false;
        }
			
		
		
		if(!isset($email)||empty($email)){
            $message .= "Please, Enter Your Email!<br/>";
            $loginAble = false;
        }else if(!preg_match("/@/",$email)){
			$message .= "Please, Enter Your Email Correctly!<br/>";
            $loginAble = false;
		}
			
		$valueLength = strlen($password);
		$max = 25;               //Maximum Password Length
		$min = 3;				//Minimum Password Length
		
		if(empty($password)){
            //Check if empty or not set
			$message .= "Please, Enter Your Password!<br/>";
            $loginAble = false;
        }else if($valueLength<$min || $valueLength>$max){
            //Minimum and Maximum Password Length check 
			$message .="Please, Choose a password between 3 to 25 Characters Length!<br/>";
            $loginAble = false;
        }							  
        
        //Checking if two passwords are matching
        if($_POST["password"] != $_POST["password1"]){
            $message .="Confirm your password correctly!";
            $loginAble = false;
        }
    
    //Password encrypting before enterting into database
    $password = password_hash($password, PASSWORD_BCRYPT);
    //SQL query for checking if the email exist
    $_sql1 = "SELECT email FROM user";
    $result = $mysqli->query($_sql1);
    
    while($row = $result->fetch_assoc()){
        if($row["email"] === $email){
            $loginAble = false;
            $message .= "Email exists already!";
        }
    }
        
    //SQL Activities
    $_sql = "INSERT INTO user "; 
    $_sql .="(full_name, email, password) ";
    $_sql .="VALUES ('$fullname', '$email', '$password') ";
    
    
    
        
        
        
        
    if($loginAble){  //Checks whether the form matches all validation.
        if($mysqli->query($_sql)===true){
            $message = "Registration Successful!";
        }else{
            echo "Not connected. Error: ".$mysqli->error;
        }
    }
    
        
	}else{                                           //When the submit button isn't pressed
		$fullname = null;
		$email = null;
		$message = null;
	}
?>






<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
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
<div class="login-form">
<h1>Sign Up </h1>

<form action="signup.php" method="post">
        <h2 style="font-size:25px;">Full Name:</h2>
        <input type="text" class="text" value="<?php echo $fullname; ?>" name="fullname" required>

        <h2 style="font-size:25px;">Email:</h2>
        <input type="text" class="text" value="<?php echo $email; ?>"  name="email" required>

        <h2 style="font-size:25px;">Password:</h2>
        <input type="password" value="" name="password" required>
        
        <h2 style="font-size:25px;">Confirm Password:</h2>
        <input type="password" value="" name="password1" required>
    
        <p style="color:red;"><b><?php echo $message; ?></b></p>	<!--All Common Error Message Here -->
                <br/>

        <div class="submit">
            <input type="submit" value="SIGN UP" name="submit">
    </div>	
    <!--Creating a new account button -->
    <a href="index.php">
        <input type="button" value="LOGIN" >
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