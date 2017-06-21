<?php 
    session_start();
    if(!$_SESSION["email"]){           //Checks if a session is started
        //Redirects to login page if a session isn't started
        header("location:index.php"); 
        exit;
    }
?>
<?php
    require_once("includes/session_timeout.php");   //Session Time Out
    require_once("includes/connect.php");   //Database connection
?>

<?php 
        $processedEmail = $_SESSION["email"];
        $processedEmail = str_replace("@","",$processedEmail);
        $processedEmail = str_replace(".","",$processedEmail);
        
        $skillFieldTable = $processedEmail."_skill";

        if(isset($_POST["UpdateSkillFieldName"])){
            if($_SESSION["csrf_token"] == $_POST['csrf_token'] )
            {
                $selectedSkill = $_POST["selected"];
                $newSkillName = $mysqli->real_escape_string($_POST["newSkillFieldName"]);
                $newSkillName = ucwords($newSkillName);
                // Query for editing a skill name 
                $_sql7 = "UPDATE $skillFieldTable "; 
                $_sql7.= "SET skill_field_name = '".$newSkillName."' ";
                $_sql7.= "WHERE skill_field_name = '".$selectedSkill."'";

                $result3 = $mysqli->query($_sql7);

                //Query for updating in Contact Table'
                $_sqlUpdateContactTable = "UPDATE $processedEmail ";
                $_sqlUpdateContactTable .= "SET skill_field = '".$newSkillName."' ";
                $_sqlUpdateContactTable .= "WHERE skill_field = '".$selectedSkill."'";

                $queryForUpdateContactTable = $mysqli->query($_sqlUpdateContactTable);

                if(!$queryForUpdateContactTable){
                    echo "Error! Updating Skill Name into Contact table has been failed!";
                }

                $success = true; 
            }
        }
        
        if(isset($_POST["deleteSkill"])){
            if($_SESSION["csrf_token"] == $_POST['csrf_token'] )
            {
                $selectedSkill1 = $_POST["selected"];
                // Query for deleting a skill name in Skill Table
                $_sql8 = "DELETE from $skillFieldTable "; 
                $_sql8.= "WHERE skill_field_name = '".$selectedSkill1."'";

                $result5 = $mysqli->query($_sql8);

                // Query for deleting a skill name in Contact Table
                $_sql9 = "UPDATE $processedEmail "; 
                $_sql9 .= "SET skill_field = 'None' ";
                $_sql9 .= "WHERE skill_field = '".$selectedSkill1."'";

                $result6 = $mysqli->query($_sql9);


                $deleteSuccess = true;
            }
        }

        if(isset($_POST['submit'])){
            
            if($_SESSION["csrf_token"] == $_POST['csrf_token'] )
            {
                $skillFieldName = $mysqli->real_escape_string($_POST["skillfield"]);
                $skillFieldName = ucwords($skillFieldName);
                $sql4 = "INSERT INTO $skillFieldTable (skill_field_name) ";
                $sql4 .= "values ('".$skillFieldName."')";

                $resultSkill = $mysqli->query($sql4);
                $skillFieldAdded = true;
         
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
    
    <form action="skill_fields.php" method="post">
        <?php $_SESSION["csrf_token"] = time();  ?>
        <input type="hidden" name="csrf_token" value=" 
        <?php print(htmlspecialchars($_SESSION["csrf_token"]));?>"> 
        <h1>Add a new Skill Field</h1><br>
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
        <input type="hidden" name="csrf_token" value=" 
        <?php print(htmlspecialchars($_SESSION["csrf_token"]));?>">
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
        <!-- code for ignoring refresh submission -->
        <input type="hidden" name="csrf_token" value=" 
        <?php print(htmlspecialchars($_SESSION["csrf_token"]));?>">
        
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
