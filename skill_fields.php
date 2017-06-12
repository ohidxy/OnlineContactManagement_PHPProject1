<?php 
    session_start();
    if(!$_SESSION["email"]){           //Checks if a session is started
        //Redirects to login page if a session isn't started
        header("location:index.php"); 
        exit;
    }
?>
<?php
    define("DB_HOST","localhost");
    define("DB_USER","root");
    define("DB_PASS","");
    define("DB_NAME","ocm");

    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
?>

<?php 
         
        if(isset($_POST["submitNewSkillFieldName"])){
            $selectedSkill = $_POST["selected"];
            $newSkillName = $mysqli->real_escape_string($_POST["newSkillFieldName"]);
             
            // Query for editing a skill name 
            $_sql7 = "UPDATE skill_field "; 
            $_sql7.= "SET skill_field_name = '".$newSkillName."' ";
            $_sql7.= "WHERE skill_field_name = '".$selectedSkill."'";

            $result3 = $mysqli->query($_sql7);
            $success = true; 
        }
        
        if(isset($_POST["deleteSkill"])){
            $selectedSkill1 = $_POST["selected"];
            // Query for deleting a skill name 
            $_sql8 = "DELETE from skill_field "; 
            $_sql8.= "WHERE skill_field_name = '".$selectedSkill1."'";

            $result5 = $mysqli->query($_sql8);
            $deleteSuccess = true; 
        }

        if(isset($_POST['submit'])){
            $skillFieldName = $mysqli->real_escape_string($_POST["skillfield"]);
            
            $sql4 = "INSERT INTO skill_field (skill_field_name) ";
            $sql4 .= "values ('".$skillFieldName."')";

            $resultSkill = $mysqli->query($sql4);
            $skillFieldAdded = true;
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
</head>
<body style="">
<!-- Navigation Container -->
<div class="container" style="width:1000px;">
    <br>
    <p><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
  <ul class="nav nav-pills nav-justified">
    <li><a href="view_contact.php">View Contact</a></li>
    <li><a href="create_contact.php">Create New Contact</a></li>
    <li  class="active"><a href="skill_fields.php">Skill Fields</a></li>
    <li><a href="logout.php">Log Out</a></li>
  </ul>
    <br>
<center>
    
    <form action="skill_fields.php" method="post">
        <h1>Add a new Skill Field</h1><br>
        <input type="text" name="skillfield" placeholder="Enter the New Skill Field Name here" required>
        <input class="btn btn-success" style="margin-left:0px;"  type="submit" value="Submit" name="submit">
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
    <br><br>
    <form action="skill_fields.php" method="post">
        <?php 
            $sql2 = "SELECT * from skill_field ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
           
            echo "<select name=\"selected\">";
            while($row = $result2->fetch_assoc()){
                print("<option>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
        ?>
        <input type="text" name="newSkillFieldName" placeholder="Enter the edited name" required>
        
        <input class="btn btn-success"   type="submit" value="Submit" name="submitNewSkillFieldName">
        
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
            $sql2 = "SELECT * from skill_field ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
           
            echo "<select name=\"selected\">";
            while($row = $result2->fetch_assoc()){
                print("<option>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
        ?>
        
        <input class="btn btn-danger"  type="submit" value="DELETE" name="deleteSkill">
        
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
</div>

    
    
  
</body>
</html>
