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
<div class="container" style="width:1000px; border-style: solid;
    border-width: 3px; border-radius:10px; margin-top:10px;">
    <br>
    <p><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
  <ul class="nav nav-pills nav-justified">
    <li class="active"><a href="view_contact.php">View Contact</a></li>
    <li><a href="create_contact.php">Create New Contact</a></li>
    <li><a href="skill_fields.php">Skill Fields</a></li>
    <li><a href="logout.php">Log Out</a></li>
  </ul>
    <br><br>
    

  <!-- Filter Option for Data Field starts-->
  <p><b>Filtered By:</b></p>
  <?php 
            $sql2 = "SELECT * from skill_field ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
           
            echo "<select name=\"selected\">";
            while($row = $result2->fetch_assoc()){
                print("<option>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
    ?>  

 
 <!-- Filter Option for Data Field Ends-->

  <!--Search Box -->  
  <input type="text" name="search" placeholder="Search..">  

    
  
    <!-- View Contact Data Table Starts -->
  <div class="table-responsive"><br/>           
  <table class="table" align="center">
    <thead>
      <tr>
        <th>#</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>H/P No</th>
        <th>Skill Field</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Anna</td>
        <td>Pitt</td>
        <td>35</td>
        <td>New York</td>
        <td>
            <div class="btn-group">
              <button type="button"  class="btn btn-primary">View</button>
              <button type="button" class="btn btn-primary">Edit</button>
            </div>
        </td>
      </tr>
    </tbody>
  </table>
  </div> 
    <!-- View Contact Data Table Ends --> 
    
</div>
</body>
</html>
