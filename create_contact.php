<?php 
    session_start();
        
    if(!$_SESSION["email"]){           //Checks if a session is started
        //Redirects to login page if a session isn't started
        header("location:index.php");
        exit;
    }

    $processedEmail = $_SESSION["email"];
    $processedEmail = str_replace("@","",$processedEmail);
    $processedEmail = str_replace(".","",$processedEmail);
    $skillFieldTable = $processedEmail."_skill";
?>

<?php
    require_once("includes/session_timeout.php");   //Session Time Out
    require_once("includes/connect.php");   //Database connection
?>

<?php
    if(isset($_POST["submitCreateForm"])){
        $firstName = $mysqli->real_escape_string($_POST["firstname"]);
        $firstName = trim($firstName," ");  //Trimming WhiteSpace

        $lastName = $mysqli->real_escape_string($_POST["lastname"]);
        $lastName = trim($lastName," ");  //Trimming WhiteSpace
        
        $email = $mysqli->real_escape_string($_POST["email"]);
        $email = trim($email," ");  //Trimming WhiteSpace
        
        $skillField = $mysqli->real_escape_string($_POST["selected"]);
        $address = $mysqli->real_escape_string($_POST["address"]);
        $website = $mysqli->real_escape_string($_POST["website"]);
        
        $linkedin = $mysqli->real_escape_string($_POST["linkedin"]); 
        $linkedin = trim($linkedin," ");  //Trimming WhiteSpace
        
        $hpNo = $mysqli->real_escape_string($_POST["hpno"]);
        $hpNo = trim($hpNo," ");  //Trimming WhiteSpace
        
        $twtnfb = $mysqli->real_escape_string($_POST["twtnfb"]);
        $twtnfb = trim($twtnfb," ");  //Trimming WhiteSpace
        
        $company = $mysqli->real_escape_string($_POST["company"]);
        
        /*echo $firstName."<br>".$lastName."<br>".$email."<br>".$skillField."<br>".$address."<br>".$website."<br>".$linkedin."<br>".$hpNo."<br>".$twtnfb."<br>".$company;*/
        
        
        
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
        $createSql.=")";
        
        $processSql = $mysqli->query($createSql);
        
		$addSuccess = true; //For successful Add Message
        if(!$processSql){
            echo "SQL Error during creating table";
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
<div class="container" style="min-height:680px;width:1000px; border-style: solid;
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
    <a class="btn btn-warning btn-sm" href="#">Updates</a>
    <a class="btn btn-warning btn-sm" href="#">Bug Report</a>
    <a class="btn btn-warning btn-sm" href="#">Feedback</a>
</feedback>    

  <br>
 <center>  
     <br><br><br>
    <h1 style="margin-left:20px;">Create a New Contact</h1><br>
    <form style="width:550px;margin-left:20px;" method="post">
        <input placeholder="First Name (Required)" type="text" name="firstname" required>
        <input placeholder="Last Name (Required)" type="text" name="lastname" required>
        <input placeholder="Email (Required)" type="text" name="email" required>
        
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
        
        <input placeholder="Address" type="text" name="address">
        <input placeholder="Website URL" type="text" name="website">
        <input placeholder="LinkedIn URL" type="text" name="linkedin">
        <input placeholder="H/P No" type="text" name="hpno">
        <input placeholder="Twitter/FB URL" type="text" name="twtnfb">
        <input placeholder="Company" type="text" name="company">

		<?php  //Success Message
            if(isset($addSuccess)){
                if($addSuccess){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> You have added a new contact!
                        </div>";
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
