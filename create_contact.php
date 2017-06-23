<?php 
    session_start();
    
    require_once("includes/session_timeout.php");   //Session Time Out
    require_once("includes/connect.php");   //Database connection
    require_once("includes/session_management.php");   //Session Settings
	require_once("includes/Token.php");   //CSRF Token Class

    if(!isset($_SESSION["email"])){           //Checks if a session is started
        //Redirects to login page if a session isn't started
        header("Location:index.php");   
        exit;
    }else{
        before_every_protected_page();
    }

    $processedEmail = $_SESSION["email"];
    $processedEmail = str_replace("@","",$processedEmail);
    $processedEmail = str_replace(".","",$processedEmail);
    $skillFieldTable = $processedEmail."_skill";
?>

<?php
    if(isset($_POST["submitCreateForm"])){
        if(Token::checkToken($_POST["csrf_token"]))
        {
            $firstName = $mysqli->real_escape_string($_POST["firstname"]);
            $firstName = trim($firstName," ");  //Trimming WhiteSpace
            $firstName = ucwords($firstName);
            
            $lastName = $mysqli->real_escape_string($_POST["lastname"]);
            $lastName = trim($lastName," ");  //Trimming WhiteSpace
            $lastName = ucwords($lastName);
            
            $email = $mysqli->real_escape_string($_POST["email"]);
            $email = trim($email," ");  //Trimming WhiteSpace
            $email = strtolower($email);    
            
            $skillField = $mysqli->real_escape_string($_POST["selected"]);
            $address = $mysqli->real_escape_string($_POST["address"]);
            $website = $mysqli->real_escape_string($_POST["website"]);
            $website = strtolower($website);
            
            $linkedin = $mysqli->real_escape_string($_POST["linkedin"]); 
            $linkedin = trim($linkedin," ");  //Trimming WhiteSpace
            $linkedin = strtolower($linkedin);
            
            $hpNo = $mysqli->real_escape_string($_POST["hpno"]);
            $hpNo = trim($hpNo," ");  //Trimming WhiteSpace

            $twtnfb = $mysqli->real_escape_string($_POST["twtnfb"]);
            $twtnfb = trim($twtnfb," ");  //Trimming WhiteSpace
            $twtnfb = strtolower($twtnfb);
            
            $company = $mysqli->real_escape_string($_POST["company"]);
            $company = trim($company," ");  //Trimming WhiteSpace
            $company = ucwords($company);
            
            
            
            ///SQL for checking if the email exists already///
            $checkEmail = "SELECT email FROM $processedEmail WHERE email = '$email'";
            $checkEmailQuery = $mysqli->query($checkEmail);
            $isEmailExist = false;
            if($mysqli->affected_rows > 0){
                 $isEmailExist = true;
            }
            
        
            /*
            $createSql = "INSERT INTO $processedEmail ";
            $createSql.= "(first_name, last_name, email, skill_field, address, website, linkedin, hp_no, twitter_fb, company) ";
            $createSql.="value (";
            $createSql.="'$firstName', ";
            $createSql.="'$lastName', ";
            $createSql.="'$email', ";
            $createSql.="'$skillField', ";
            $createSql.="'$address', ";
            $createSql.="'$website', ";
            $createSql.="'$linkedin', ";
            $createSql.="'$hpNo', ";
            $createSql.="'$twtnfb', ";
            $createSql.="'$company'";
            $createSql.=")";   */
            
            
            //SQL Prepared Statements for Inserting Contact Data
            $_SQL ="INSERT INTO $processedEmail (first_name, last_name, email, skill_field, address, website, linkedin, hp_no, twitter_fb, company) VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
            $stmt = $mysqli->prepare($_SQL);
            
            $stmt->bind_param('ssssssssss', $firstName, $lastName, $email, $skillField, $address, $website, $linkedin, $hpNo, $twtnfb, $company);
            //////////////////////////
            
            
            $addSuccess = false;
            if($isEmailExist === false){
                $stmt->execute();
                
                //$processSql = $mysqli->query($createSql);
                $addSuccess = true; //For successful Add Message
            }else{
                $addSuccess = false;
            }
            $stmt->close();
            
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard | Online Contact Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    
   <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet"> 
  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
  <link href="css/dashboard.css" rel='stylesheet' type='text/css' />
    
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body style="">
<!-- Navigation Container -->
<div class="container" style="min-height:680px; border-style: solid;
    border-width: 3px; border-radius:10px; margin-top:10px; margin-bottom:50px;">
    <br>
    <p><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
    
  <ul class="nav nav-pills nav-justified">
    <li><a href="view_contact.php">View Contact</a></li>
    <li  class="active"><a href="create_contact.php">Create New Contact</a></li>
    <li><a href="skill_fields.php">Skill Fields</a></li>
    <li><a href="logout.php">Log Out</a></li>
  </ul>
    <!-- Feedback and Bug Report Buttons -->
<feedback style="margin-top:5px; float:right;">
    <a class="btn btn-warning btn-sm" href="updates.html" target="_blank">Updates</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/dkvJLzxftGfC1AIG3" target="_blank">Bug Report</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/w9zM6ECw5qtLKiXH3" target="_blank">Feedback</a>
</feedback>    

  <br>
 <center>  
     <br><br><br>
	 
	
    
    <h1 style="margin-left:20px;">Create a New Contact</h1><br>
    <form style="width:550px;margin-left:20px;" method="post">
        <!-- Code for avoiding data duplication -->
        <input type="hidden" name="csrf_token" value="<?php echo Token::generateToken(); //Generating the Token  ?>">    
        <!--------->
        <input placeholder="First Name (Required)" type="text" name="firstname" value ="<?php if(isset($firstName)){echo $firstName;} ?>" required>
        
        <input placeholder="Last Name (Required)" type="text" name="lastname" value ="<?php if(isset($lastName)){echo $lastName;} ?>" required>
        
        <input placeholder="Email (Required)" type="email" name="email" value ="<?php if(isset($email)){echo $email;} ?>" required>
        
        <!--Skill Field -->
        <!--PHP COde for Selecting Categories -->
        
        <?php 
            
            $sql2 = "SELECT * from $skillFieldTable ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
           
            echo "<select name=\"selected\">";
			echo "<option>None</option>";
            while($row = $result2->fetch_assoc()){
                print("<option>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
        ?>
        
        <input placeholder="Address" type="text" name="address" value ="<?php if(isset($address)){echo $address;} ?>">
        <input placeholder="Website URL" type="text" name="website" value ="<?php if(isset($website)){echo $website;} ?>">
        <input placeholder="LinkedIn URL" type="text" name="linkedin" value ="<?php if(isset($linkedin)){echo $linkedin;} ?>">
        <input placeholder="H/P No" type="text" name="hpno" value ="<?php if(isset($hpNo)){echo $hpNo;} ?>">
        <input placeholder="Twitter/FB URL" type="text" name="twtnfb" value ="<?php if(isset($twtnfb)){echo $twtnfb;} ?>">
        <input placeholder="Company" type="text" name="company" value ="<?php if(isset($company)){echo $company;} ?>">

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
		
		<input class="btn btn-success" style="float:right;margin-top:0px;margin-right:25px;width:100px;font-size:18px;" type="submit" value="Submit" name="submitCreateForm">
	</form>
     <br><br><br>
</center>   
<br><br><br><br><br>
<center>Copyright © 2017 ohid.info</center>     
</div> 
</body>
</html>
