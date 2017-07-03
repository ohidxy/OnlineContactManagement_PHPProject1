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

  if(isset($_POST["saveUserData"]))
  {   
    if($_POST["csrf_user_information"] == $_SESSION["csrf_user_information"]){
          $fullname = $mysqli->real_escape_string($_POST["fullname"]);
          $fullname = trim($fullname," ");  //Trimming WhiteSpace
          $fullname = htmlspecialchars($fullname);
          
          //Immediate effect of name in Session
          $_SESSION["fullname"] = $fullname;
          //////////////////////////////////

          $email = $mysqli->real_escape_string($_POST["email"]);
          $email = trim($email," ");  //Trimming WhiteSpace
          $email = htmlspecialchars($email);
          
          //$skillField = $mysqli->real_escape_string($_POST["selected"]);
          $address = $mysqli->real_escape_string($_POST["address"]);
          $address = htmlspecialchars($address);
          
          $website = $mysqli->real_escape_string($_POST["website"]);
          $website = htmlspecialchars($website);
          
          $linkedin = $mysqli->real_escape_string($_POST["linkedin"]); 
          $linkedin = trim($linkedin," ");  //Trimming WhiteSpace
          $linkedin = htmlspecialchars($linkedin);
          
          $hpNo = $mysqli->real_escape_string($_POST["hpno"]);
          $hpNo = trim($hpNo," ");  //Trimming WhiteSpace
          $hpNo = htmlspecialchars($hpNo);
          
          $twtnfb = $mysqli->real_escape_string($_POST["twtnfb"]);
          $twtnfb = trim($twtnfb," ");  //Trimming WhiteSpace
          $twtnfb = htmlspecialchars($twtnfb);
          
          $company = $mysqli->real_escape_string($_POST["company"]);
          $company = htmlspecialchars($company);
          
          $useremail = $email;

          $saveForm = "UPDATE user ";
          $saveForm.= "SET ";
          $saveForm.="full_name = ?, ";
          $saveForm.="email = ?, ";
          $saveForm.="address = ?, ";
          $saveForm.="website = ?, ";
          $saveForm.="linkedin = ?, ";
          $saveForm.="hp_no = ?, ";
          $saveForm.="twitter_fb = ?, ";
          $saveForm.="company = ?";
          $saveForm.=" WHERE email = '$email'";
          
          $stmt = $mysqli->prepare($saveForm);
          
          $stmt->bind_param('ssssssss', $fullname, $email, $address, $website, $linkedin, $hpNo, $twtnfb, $company);
          $stmt->execute();
          $stmt->close();
          //////////////////////////
          $saveSuccess = true; //For successful Add Message
      }
  }


  if(isset($_POST["changePasswordForm"])){
          if($_POST["csrf_change_password"] == $_SESSION["csrf_change_password"]){
                $currentPassword = $mysqli->real_escape_string($_POST['currentPassword']);
                $currentPassword = htmlspecialchars($currentPassword); 

                $newPassword = $mysqli->real_escape_string($_POST['newPassword']); 
                $newPassword = htmlspecialchars($newPassword);

                $confirmPassword = $mysqli->real_escape_string($_POST['confirmPassword']); 
                $confirmPassword = htmlspecialchars($confirmPassword);
                
                $email =$_SESSION["email"];

                $sqlPasswordSearch = "SELECT password FROM user WHERE email = '$email'";
                $sqlPassSearchQuery = $mysqli->query($sqlPasswordSearch);
                
                while($row = $sqlPassSearchQuery->fetch_assoc()){
                      if(password_verify($currentPassword, $row["password"])){
                            $currentPasswordMatch = true;
                      }
                }
                
                //Password encrypting before enterting into database
                $finalConfirmPassword = password_hash($confirmPassword, PASSWORD_BCRYPT);

                $sqlPasswordUpdate = "UPDATE user SET password = ? WHERE email = ?";
                $stmt = $mysqli->prepare($sqlPasswordUpdate);
                $stmt->bind_param('ss', $finalConfirmPassword, $email);

                if(isset($currentPasswordMatch) && ($newPassword == $confirmPassword)){
                       $stmt->execute();
                       $stmt->close();
                       $changePasswordSuccess = true;
                }   else{
                    $changePasswordFailure = true;
                }
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
    
    <!--******************* PROFILE SETTINGS ****************** -->
    <!--******************* PROFILE SETTINGS ****************** -->
    <!--******************* PROFILE SETTINGS ****************** -->
    
    <?php
        //Responsible for retriving user data 
            $_sqlInfoSearch = "SELECT * FROM user ";
            $_sqlInfoSearch.="WHERE email='".$_SESSION["email"]."'";   
        
            $findInfo = $mysqli->query($_sqlInfoSearch);

            //Method for pasting User data into input field of View Contact > More
            while($row = $findInfo->fetch_assoc()){
    ?> 
    <center >
    <form style="margin:20px;border-style:solid;border-color:#BDBDBD;border-width:2px;border-radius:5px;" method="post">
      <h1 style="margin-left:20px;">Your Profile</h1><br>
        <!-- Code for avoiding data duplication -->
        <?php 
            $_SESSION['csrf_user_information'] = base64_encode(openssl_random_pseudo_bytes(32));
        ?>
        <input type="hidden" name="csrf_user_information" value="<?php echo $_SESSION['csrf_user_information'] ?>">
        
        <center style="width:600px;padding-bottom:10px;">
        <input placeholder="Full Name" type="text" name="fullname" value ="<?php echo $row["full_name"]; ?>" required>
        <input placeholder="H/P No" type="text" name="hpno" value ="<?php echo $row["hp_no"]; ?>">
        <input placeholder="Address" type="text" name="address" value ="<?php echo $row["address"]; ?>">
        <input placeholder="Website/Github URL" type="text" name="website" value ="<?php echo $row["website"]; ?>">
        <input placeholder="LinkedIn URL" type="text" name="linkedin" value ="<?php echo $row["linkedin"]; ?>">
        <input placeholder="Twitter/FB URL" type="text" name="twtnfb" value ="<?php echo $row["twitter_fb"]; ?>">
        <input placeholder="Company" type="text" name="company" value ="<?php echo $row["company"]; ?>">

        <!-- Skill Field Goes Here -->
        
        <input placeholder="email" type="text" name="email" value="<?php echo $row["email"]; ?>" readonly>

        <?php } //end tag of while loop for searching DB data ?> 

		<?php  //Success Message
            if(isset($saveSuccess)){
                if($saveSuccess){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> Your information has been saved!
                        </div>";
                }
            }
        ?>
		
		<input class="btn btn-success" style="float:center;margin-left:400px;width:100px;height:30px;font-size:18px;" type="submit" value="Save" name="saveUserData">
    </center>
  </form>
  </center>
  <!--****************************************************************-->
  <!--****************************************************************-->
  <!--****************************************************************-->

    <!-- ********************* CHANGE PASSWORD ********************* -->  
    <!-- ********************* CHANGE PASSWORD ********************* --> 
    <!-- ********************* CHANGE PASSWORD ********************* --> 
    <center >
    <form style="margin:20px;border-style:solid;border-color:#BDBDBD;border-width:2px;border-radius:5px;" method="post">
      <h1 style="margin-left:20px;">Change Password</h1><br>
        <!-- Code for avoiding data duplication -->
        <?php 
            $_SESSION['csrf_change_password'] = base64_encode(openssl_random_pseudo_bytes(32));
        ?>
        <input type="hidden" name="csrf_change_password" value="<?php echo $_SESSION['csrf_change_password'] ?>">    
        
        <input placeholder="Current Password" type="password" name="currentPassword" value ="">
        <input placeholder="New Password" type="password" name="newPassword" value ="">
        <input placeholder="Confirm Password" type="password" name="confirmPassword" value ="">
        <input class="btn btn-success" style="float:center;margin-left:59px;width:100px;height:30px;font-size:18px;" type="submit" value="Save" name="changePasswordForm">
		<?php  //Success Message
            if(isset($changePasswordSuccess)){
                if($changePasswordSuccess){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> You have changed the password!
                        </div>";
                }
            }

            //Failure Message
            if(isset($changePasswordFailure)){
                if($changePasswordFailure){
                    echo "<div class=\"alert alert-danger\">
                            <strong>Sorry!</strong> Any of the passwords doesn't match. Please try again!
                        </div>";
                }
            }
        ?>
	</form>
  </center>
  <!-- **********************************************************-->

  <!-- **************************** SESSION TIMEOUT ********************* -->
  <!-- **************************** SESSION TIMEOUT ********************* -->
  <!-- **************************** SESSION TIMEOUT ********************* -->
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
        
        	<input class="btn btn-success" style="float:center;margin-left:149px;width:100px;height:30px;font-size:18px;" type="submit" value="Save" name="submitSessionForm">
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
