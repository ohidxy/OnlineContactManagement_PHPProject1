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
        
        $skillFieldTable = $processedEmail."_skill";
        
        
        if(isset($_POST['submit'])){
            
            if($_POST["csrf_add_field"] == $_SESSION["csrf_add_field"])
            {
                $skillFieldName = $mysqli->real_escape_string($_POST["skillfield"]);
                $skillFieldName = strip_tags($skillFieldName);
                $skillFieldName = trim($skillFieldName);
                $skillFieldName = ucwords($skillFieldName);
                
                $sql4 = "INSERT INTO $skillFieldTable (skill_field_name) ";
                $sql4 .= "values ( ? )";
                
                //Prepare statement for entering new SKILL FIELD
                $stmt = $mysqli->prepare($sql4);
                $stmt->bind_param("s", $skillFieldName);
                $stmt->execute();
                $stmt->close();
                
                $resultSkill = $mysqli->query($sql4);
                $skillFieldAdded = true;
         
            }
        }

        if(isset($_POST["UpdateSkillFieldName"])){
            if($_POST["csrf_edit_field"] == $_SESSION["csrf_edit_field"])
            {
                $selectedSkill = $_POST["selected"];
                $newSkillName = $mysqli->real_escape_string($_POST["newSkillFieldName"]);
                $newSkillName = strip_tags($newSkillName);
                $newSkillName = trim($newSkillName);
                $newSkillName = ucwords($newSkillName);
                
                
                // Query for editing a skill name 
$_sql7 = "UPDATE $skillFieldTable SET skill_field_name = ? WHERE skill_field_name = ? ";
                $stmt = $mysqli->prepare($_sql7);
                $stmt->bind_param("ss", $newSkillName, $selectedSkill);
                $stmt->execute();
                $stmt->close();

                //Query for updating in Contact Table'
$_sqlUpdateCntctTbl = "UPDATE $processedEmail SET skill_field = ? WHERE skill_field = ? ";
                $stmt = $mysqli->prepare($_sqlUpdateCntctTbl);
                $stmt->bind_param("ss", $newSkillName, $selectedSkill);
                $stmt->execute();
                $stmt->close();

                $success = true; 
            }
        }
        
        if(isset($_POST["deleteSkill"])){
            if($_POST["csrf_delete_field"] == $_SESSION["csrf_delete_field"])
            {
                $selectedSkill1 = $_POST["selected"];
                
                // Query for deleting a skill name in Skill Table
                $_sql8 = "DELETE from $skillFieldTable "; 
                $_sql8.= "WHERE skill_field_name = ? ";

                $stmt = $mysqli->prepare($_sql8);
                $stmt->bind_param("s",$selectedSkill1);
                $stmt->execute();
                $stmt->close();
                
                
                // Query for deleting a skill name in Contact Table
                $_sql9 = "UPDATE $processedEmail "; 
                $_sql9 .= "SET skill_field = 'None' ";
                $_sql9 .= "WHERE skill_field = ? ";

                $stmt = $mysqli->prepare($_sql9);
                $stmt->bind_param("s",$selectedSkill1);
                $stmt->execute();
                $stmt->close();


                $deleteSuccess = true;
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
    <br>
    <p><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
  <ul class="nav nav-pills nav-justified">
    <li><a href="view_contact.php">View Contact</a></li>
    <li><a href="create_contact.php">Create New Contact</a></li>
    <li  class="active"><a href="skill_fields.php">Skill Fields</a></li>
    <li><a href="logout.php">Log Out</a></li>
  </ul>
    
    <!-- Feedback and Bug Report Buttons -->
<feedback style="margin-top:5px; float:right;">
    <a class="btn btn-warning btn-sm" href="updates.html" target="_blank">Updates</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/dkvJLzxftGfC1AIG3" target="_blank">Bug Report</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/w9zM6ECw5qtLKiXH3" target="_blank">Feedback</a>
</feedback>  
    <br><br>
<center>
    <h1>Add a new Skill Field</h1><br>
    <form action="skill_fields.php" method="post">
        <?php 
            $_SESSION['csrf_add_field'] = base64_encode(openssl_random_pseudo_bytes(32));
        ?>
        <input type="hidden" name="csrf_add_field" value="<?php echo $_SESSION['csrf_add_field'] ?>"> 
        
        <input type="text" name="skillfield" placeholder="Enter a new skill field here...." required>
        <input class="btn btn-success" style="margin-top:0px;margin-right:0px;font-size:18px;height:36px;" type="submit" value="Submit" name="submit">
        <?php
            if(isset($skillFieldAdded)){
                if($skillFieldAdded){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> You have added a skill field. Skill Field Name: <strong>".$skillFieldName."</strong>
                        </div>";
                }
            }
        ?>
        
    </form>
    

    <br><br>
    <h1>Edit a current Skill Field</h1><br>    

    <form action="skill_fields.php" method="post">
        <?php 
            $_SESSION['csrf_edit_field'] = base64_encode(openssl_random_pseudo_bytes(32));
        ?>
        <input type="hidden" name="csrf_edit_field" value="<?php echo $_SESSION['csrf_edit_field'] ?>"> 
        
        <?php 
            $sql2 = "SELECT * from $skillFieldTable ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
           
            echo "<select name=\"selected\">";
            while($row = $result2->fetch_assoc()){
                print("<option>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
        ?>
        <input type="text" name="newSkillFieldName" placeholder="Edit the skill field...." required>
        
        <input class="btn btn-success"  style="margin-top:0px;margin-right:0px;font-size:18px;height:36px;" type="submit" value="Update" name="UpdateSkillFieldName">
        
        <!--Success Message  -->
        <?php
        
        
            if(isset($success)){
                if($success){
                    echo "<div class=\"alert alert-success\">
                            <strong>Success!</strong> The skill name has been modified.<br> Previous Name: <strong>".$selectedSkill."</strong> New Name: <strong>".$newSkillName."</strong>
                        </div>";
                }
            }
        ?>
    </form>
    
    
    <!-- Delete a skill field -->
    <br><br>
    <h1>Delete a Skill Field</h1>
    <br>
    <form action="skill_fields.php" method="post">
        <?php 
            $_SESSION['csrf_delete_field'] = base64_encode(openssl_random_pseudo_bytes(32));
        ?>
        <input type="hidden" name="csrf_delete_field" value="<?php echo $_SESSION['csrf_delete_field'] ?>"> 
        
        <?php 
            $sql2 = "SELECT * from $skillFieldTable ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
           
            echo "<select name=\"selected\">";
            while($row = $result2->fetch_assoc()){
                print("<option>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
        ?>
        
        <input class="btn btn-danger" style="margin-top:0px;margin-right:0px;font-size:18px;height:36px;" type="submit" value="DELETE" name="deleteSkill">
        
        <?php
            if(isset($deleteSuccess)){
                if($deleteSuccess){
                    echo "<div class=\"alert alert-danger\">
                            <strong>Success!</strong> You have deleted a Skill Field. Affected Skill Field: <strong>".$selectedSkill1."</strong>
                        </div>";
                }
            }
        ?>
    </form>
</center>
<br><br>

    <!-- Footer Starts -->
    <center>Copyright © 2017 ohid.info</center> <br>
    <!-- Footer End -->
</div>

    
    
  
</body>
</html>
