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
    $processedEmail = $_SESSION["email"];
    $processedEmail = str_replace("@","",$processedEmail);
    $processedEmail = str_replace(".","",$processedEmail);
    $skillFieldTable = $processedEmail."_skill";
?>

<?php
    
    
    //Getting the Email for retrieving all Data
    $infoEmail = $mysqli->real_escape_string($_GET['emailid']);

	//checking if the email exists, else it will close the form
	$_checkUserEmail = "SELECT email FROM $processedEmail WHERE email = '$infoEmail'";
	$_checkUserEmailQuery = $mysqli->query($_checkUserEmail);
	
	while($mysqli->affected_rows === 0){
		$deleteHTML = "style=\"display:none;\"";                           //Change the style of form to invisible
		$emailFound = false;                         
		break;
	}

if(isset($_POST["saveForm"]))
{
    if(Token::checkToken($_POST["csrf_contact"]))
    {
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
        
        
        $saveForm = "UPDATE $processedEmail ";
        $saveForm.= "SET "; 
        $saveForm.="first_name = '$firstName', ";
        $saveForm.="last_name = '$lastName', ";
        $saveForm.="email = '$email', ";
        $saveForm.="skill_field = '$skillField', ";
        $saveForm.="address = '$address', ";
        $saveForm.="website = '$website', ";
        $saveForm.="linkedin = '$linkedin', ";
        $saveForm.="hp_no = '$hpNo', ";
        $saveForm.="twitter_fb = '$twtnfb', ";
        $saveForm.="company = '$company'";
        $saveForm.=" WHERE email = '$infoEmail'";
        
        $saveSQL = $mysqli->query($saveForm);
        
		$saveSuccess = true; //For successful Add Message
		
		$deleteHTML = "style=\"display:none;\"";                           //Change the style of form
    }
}
	
	
if(isset($_POST["deleteContact"]) && Token::checkToken($_POST["csrf_contact"])){
    $_sqlDeleteContact = "DELETE from $processedEmail WHERE email='$infoEmail'";

    $deleteContactSQL = $mysqli->query($_sqlDeleteContact);

    //Hiding the form upon deletion
    if($deleteContactSQL){
        $deleteContactSuccess=true;
        $deleteHTML = "style=\"display:none;\"";
    }
}
      
else{
		$deleteContactSuccess = false;
		if(!isset($_POST["saveForm"]) && !isset($emailFound)){
			$deleteHTML = "style=\"width:550px;margin-left:20px;\"";
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
<div class="container" style="min-height:680px; border-style: solid;
    border-width: 3px; border-radius:10px; margin-top:10px;">
    <br>
    <p><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
    <ul class="nav nav-pills nav-justified">
    <li class="active"><a href="view_contact.php">View Contact</a></li>
    <li><a href="create_contact.php">Create New Contact</a></li>
    <li><a href="skill_fields.php">Skill Fields</a></li>
    <li><a href="logout.php">Log Out</a></li>
  </ul>
    <br><h1 align="center">Contact Details</h1><br>
    <center>
	
	
    <?php 
            $_sqlInfoSearch = "SELECT * FROM $processedEmail ";
            $_sqlInfoSearch.="WHERE email='$infoEmail'";    
        
            $findInfo = $mysqli->query($_sqlInfoSearch);
    ?>    
		
	
    <form id="contactForm" align="center" <?php echo $deleteHTML; ?> method="post">
        <input type="hidden" name="csrf_contact" value="<?php echo Token::generateToken(); ?>">
        
       <?php 
            //Method for pasting User data into input field of View Contact > More
            while($row = $findInfo->fetch_assoc()){
        
        ?> 
        
        <input value="<?php echo $row["first_name"]; ?>" type="text" name="firstname" placeholder="First Name" required>
        <input value="<?php echo $row["last_name"]; ?>" type="text" name="lastname" placeholder="Last Name" required>
        <input value="<?php echo $row["email"]; ?>" type="email" name="email" placeholder="Email" required >
        
        <!--Skill Field -->
        <!--PHP COde for Selecting Categories -->
        
        
        <input value="<?php echo $row["address"]; ?>" type="text" name="address" placeholder="Adress">
        <input value="<?php echo $row["website"]; ?>" type="text" name="website" placeholder="Website">
        <input value="<?php echo $row["linkedin"]; ?>" type="text" name="linkedin" placeholder="LinkedIn">
        <input value="<?php echo $row["hp_no"]; ?>" type="text" name="hpno" placeholder="H/P No">
        <input value="<?php echo $row["twitter_fb"]; ?>" type="text" name="twtnfb" placeholder="Twitter/Facebook">
        <input value="<?php echo $row["company"]; ?>" type="text" name="company" placeholder="Company">
        
        <?php $selectedSkill = $row["skill_field"];  } ?>   <!-- End of Find Info SQL Query -->
        
        <?php 
            
            $sql2 = "SELECT * from $skillFieldTable ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
           
            echo "<select name=\"selected\">";
			echo "<option>None</option>";
            while($row = $result2->fetch_assoc()){
                
                if($selectedSkill===$row['skill_field_name']){
                    $selectedValueHTML="selected";
                }else{
                    $selectedValueHTML="";
                }
                
                print("<option $selectedValueHTML value ='".$row['skill_field_name']."'>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
        ?>
        <br><br><br>
		
		<!-- code for DELETE BUTTON -->
		<input class="btn btn-danger" style="float:right;margin-top:0px;margin-right:25px;font-size:13px;height:28px;" type="submit" value="DELETE" name="deleteContact">
       <!-- code for SAVE BUTTON -->
		<input class="btn btn-success" style="margin-right:5px;float:right;margin-bottom:20px;width:100px;font-size:18px;" type="submit" value="Save" name="saveForm">
	</form>
	
	<?php  //Success Message
            if(isset($saveSuccess)){
                if($saveSuccess){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> You have modified this contact!
                        </div>";
                }
            }
    ?>
	
	<?php
            if(isset($deleteContactSuccess)){
                if($deleteContactSuccess){
                    echo "<div class=\"alert alert-danger\">
                            <strong>Success!</strong> You have deleted the contact!
                        </div>";
                }
            }
    ?>
	
	
	<?php
            if(isset($emailFound)){
                if(!$emailFound){
                    echo "<div class=\"alert alert-danger\">
                            There is no such email!
                        </div>";
                }
            }
    ?>
	
    </center>
</div><br><br>
</body>
</html>
