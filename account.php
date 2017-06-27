<?php 
    session_start();
    
    require_once("includes/session_timeout.php");   //Session Time Out
    require_once("includes/connect.php");   //Database connection
    require_once("includes/session_management.php");   //Session Settings
    require_once("includes/Token.php");   //CSRF token


    if(!isset($_SESSION["email"])){           //Checks if a session is started
        //Redirects to login page if a session isn't started
        header("Location:index.php");   
        exit;
    }else{
        before_every_protected_page();
    }

?>


<?php 
        $processedEmail = $_SESSION["email"];
        $processedEmail = str_replace("@","",$processedEmail);
        $processedEmail = str_replace(".","",$processedEmail);
        

        if(isset($_POST["submitSessionForm"])){
            if($_POST["csrf_session_timeout"] == $_SESSION["csrf_session_timeout"]){
                $session_user_input = $_POST["timeoutDropDown"];
                $session_user_input = $mysqli->real_escape_string($session_user_input);
                $session_user_input = htmlspecialchars($session_user_input);

                $sql_insert_session_to_db = "UPDATE user SET session_duration = $session_user_input ";
                $sql_insert_session_to_db.= "WHERE email = '".$_SESSION['email']."'";
                $sql_insert_session_query = $mysqli->query($sql_insert_session_to_db);
                $sessionSaveSuccess = true;
          }
        }
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard | Online Contact Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
  <link href="css/dashboard.css" rel='stylesheet' type='text/css' />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
</head>
<body>
<!-- Navigation Container -->
<div class="container"  style="min-height:680px; border-style: solid;
    border-width: 3px; border-radius:10px; margin-top:10px; margin-bottom:50px;" >
    
    <?php
        //Code for show Menu Selected 
        $isTasksActive = "";
        $isAccountActive = "active";
        $isViewContactActive = "";
    ?>
    <!-- navigation code goes here -->
    <?php include("menu_navigation.php"); ?>
    <br><br>
    
    <!--*************** Profile Settings **************** -->
    <center >
    <form style="margin:20px;border-style:solid;border-color:#BDBDBD;border-width:2px;border-radius:5px;" method="post">
      <h1 style="margin-left:20px;">Your Profile</h1><br>
        <!-- Code for avoiding data duplication -->
        <input type="hidden" name="csrf_token" value="<?php echo Token::generateToken(); //Generating the Token  ?>">    
        
        <input placeholder="First Name" type="text" name="address" value ="">
        <input placeholder="Last Name" type="text" name="website" value ="">
        <input placeholder="Email" type="text" name="website" value="admin@ohid.info" readonly>

		<?php  //Success Message
            if(isset($addSuccess)){
                if($addSuccess){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> You have added a new contact!
                        </div>";
                }else if(isset($isEmailExist)){   //Email Exist Message
                    if($isEmailExist){
                        echo "<div class=\"alert alert-danger\">
								<strong>Email already exists! Please, try another email.</strong>
							</div>";
                    }
                }
            }
        ?>
		
		<input class="btn btn-success" style="float:center;margin-left:149px;width:100px;font-size:18px;" type="submit" value="Save" name="submitCreateForm">
	</form>
  </center>



    <!--********************* Change Password *********************-->  
    <center >
    <form style="margin:20px;border-style:solid;border-color:#BDBDBD;border-width:2px;border-radius:5px;" method="post">
      <h1 style="margin-left:20px;">Change Password</h1><br>
        <!-- Code for avoiding data duplication -->
        <input type="hidden" name="csrf_token" value="<?php echo Token::generateToken(); //Generating the Token  ?>">    
        
        <input placeholder="Current Password" type="password" name="address" value ="">
        <input placeholder="New Password" type="password" name="website" value ="">
        <input placeholder="Confirm Password" type="password" name="website" value ="">

		<?php  //Success Message
            if(isset($addSuccess)){
                if($addSuccess){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> You have added a new contact!
                        </div>";
                }else if(isset($isEmailExist)){   //Email Exist Message
                    if($isEmailExist){
                        echo "<div class=\"alert alert-danger\">
								<strong>Email already exists! Please, try another email.</strong>
							</div>";
                    }
                }
            }
        ?>
		
		<input class="btn btn-success" style="float:center;margin-left:149px;width:100px;font-size:18px;" type="submit" value="Save" name="submitCreateForm">
	</form>
  </center>
  <!-- **********************************************************-->



  <!-- ****************** Session Timeout **************** -->
  <?php
  //Retrieving Session duration from user table
    $sql_session_duration = "SELECT session_duration ";
    $sql_session_duration.= "FROM user WHERE email = '".$_SESSION['email']."'";

    $sql_session_query = $mysqli->query($sql_session_duration);

    global $session_duration;  //variable initialization

    while($row = $sql_session_query->fetch_assoc()){
        $session_duration = $row['session_duration'];
        
    }
    // Function for selected dropdown
    function isSelected($value){
        global $session_duration;
        if($session_duration == $value){
          return "selected";
        }else{
          return "";
        }
    }
  ?>

  <center >
    <form style="margin:20px;border-style:solid;border-color:#BDBDBD;border-width:2px;border-radius:5px;" method="post">
      <h1 style="margin-left:20px;">Session Timeout</h1>
        <h4 style="color:red;">[Keep your account more secured by logging out automatically when you're not active]</h4>
        <!-- Code for avoiding data duplication -->
        <?php 
            $_SESSION['csrf_session_timeout'] = base64_encode(openssl_random_pseudo_bytes(32));
        ?>
        <input type="hidden" name="csrf_session_timeout" value="<?php echo $_SESSION['csrf_session_timeout'] ?>"> 
  
        <select name="timeoutDropDown">
            <option <?php echo isSelected(5); ?> value="5">5 minutes</option>
            <option <?php echo isSelected(10); ?> value="10">10 minutes</option>
            <option <?php echo isSelected(20); ?> value="20">20 minutes</option>
            <option <?php echo isSelected(30); ?> value="30">30 minutes</option>
            <option <?php echo isSelected(60); ?> value="60">60 minutes</option>
            <option <?php echo isSelected(120); ?> value="120">2 Hours</option>
        </select>
        
        	<input class="btn btn-success" style="float:center;margin-left:149px;width:100px;font-size:18px;" type="submit" value="Save" name="submitSessionForm">
		<?php  //Success Message
            if(isset($sessionSaveSuccess)){
                if($sessionSaveSuccess){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> Session Timeout has been set!
                        </div>";
                }
              }
        ?>
		
	
	</form>
  </center>
  <!--*************************************************************************-->
<br><br>

    <!-- Footer code goes here -->
     <?php include("footer.php"); ?>
