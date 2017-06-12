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
<div class="container" style="width:1000px;float:center;">
    <br>
    <p><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
  <ul class="nav nav-pills nav-justified">
    <li><a href="view_contact.php">View Contact</a></li>
    <li  class="active"><a href="create_contact.php">Create New Contact</a></li>
    <li><a href="skill_fields.php">Skill Fields</a></li>
    <li><a href="logout.php">Log Out</a></li>
  </ul>
  <br>
 <center>   
    <h1 style="margin-left:20px;">Create a New Contact</h1><br>
    
    <form style="width:550px;margin-left:20px;">
        <input placeholder="First Name" type="text" >
        <select>
            <option value="">Bangladesh</option>
            <option value="">USA</option>
        </select>
        <input placeholder="Last Name" type="text" >
        <input placeholder="Address" type="text">
        <input placeholder="Email" type="text" >
        <input placeholder="Website URL" type="text">
        
        <!--Skill Field -->
        <!--PHP COde for Selecting Categories -->
        <?php 
                $sql3 = "SELECT * from skill_field ORDER BY skill_field_name ASC";
                $result3 = $mysqli->query($sql3); 
       
                echo "<select>";
                while($row = $result3->fetch_assoc()){
                    print("<option>".$row['skill_field_name']."</option>");
                }
                echo "<\select>";
        ?>
        
        
        <input placeholder="LinkedIn URL" type="text">
        <input placeholder="H/P No" type="text">
        <input placeholder="Twitter/FB URL" type="text">
        <input placeholder="Company" type="text">
        <input style="float:left;margin-top:5px;" type="file">
        
        
        <input class="btn btn-danger" style="float:right;margin-top:10px;margin-right:30px;" type="submit" value="Cancel">
        <input class="btn btn-success" style="float:right;margin-top:10px;margin-right:10px;" type="submit" value="Submit">
        
    </form>
</center>    
</div>

    
    
    
</body>
</html>
