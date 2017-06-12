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
    
    
    <?php
        if(isset($_POST['submit'])){
            $skillFieldName = $mysqli->real_escape_string($_POST["skillfield"]);
            
            $sql4 = "INSERT INTO skill_field (skill_field_name) ";
            $sql4 .= "values ('".$skillFieldName."')";

            $resultSkill = $mysqli->query($sql4);
        }
    ?>
    <form action="skill_fields.php" method="post">
        <h1>Add a new Skill Field</h1><br>
        <h4><b>Skill Field Name:</b></h4><input type="text" name="skillfield"><br>
        <input class="btn btn-success" style="margin-left:160px;"  type="submit" value="Submit" name="submit">
    </form>
    
    
    
    
  <!--PHP COde for Selecting Categories -->
    
    <?php 
            $sql2 = "SELECT * from skill_field ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
    ?>  
    <br><br><br>
    <h1>Edit a current Skill Field</h1><br>
    <h4><b>Choose a Skill Field</b></h4>
    <?php 
        echo "<select>";

        while($row = $result2->fetch_assoc()){
            print("<option>".$row['skill_field_name']."</option>");
        }
        
        echo "<\select>";
    ?>
    <br><br>
    <form action="skill_fields.php" method="post">
        <h4><b>New Name:</b></h4><input type="text" ><br>
        <input class="btn btn-success" style="margin-left:160px;"  type="submit" value="Submit">
    </form>
</center>
    <br><br>
</div>

    
    
  
</body>
</html>
