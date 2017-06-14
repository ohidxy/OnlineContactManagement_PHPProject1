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
            $sql2 = "SELECT * from $skillFieldTable ORDER BY skill_field_name ASC";
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
      <tr style="font-size:18px;">
        <th>#</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>H/P No</th>
        <th>Skill Field</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
	
		<!-- PHP Code for showing row data -->	
      <?php
		
		
		
		//SQL for selecting all the contact information
		$sqlC = "SELECT * FROM $processedEmail";
	    $querySQLC = $mysqli->query($sqlC);
		
		
		
		
		while($row = $querySQLC->fetch_assoc()){
			$html = "<tr>";
			$html .="<td style=\"font-size:18px;  padding-top:12px;\">-</td>";
			$html .="<td style=\"font-size:18px; width:250px; padding-top:12px;\">".$row["first_name"]." ".$row["last_name"]."</td>";
			$html .="<td style=\"font-size:18px;  padding-top:12px;\">".$row["email"]."</td>";
			$html .="<td style=\"font-size:18px;  padding-top:12px;\">".$row["hp_no"]."</td>";
			$html .="<td style=\"font-size:18px;  padding-top:12px;\">".$row["skill_field"]."</td>";
			
			$html .='<td>
				<div class="btn-group">
				  <button type="button"  class="btn btn-primary">View</button>
				  <button type="button" class="btn btn-primary">Edit</button>
				</div>
			</td>';
		$html .="</tr>";
			
			print($html);
		}
		
		
	  ?>
	  <!-- PHP Code for showing row data ends -->
    </tbody>
  </table>
  </div> 
    <!-- View Contact Data Table Ends --> 
    
</div>
</body>
</html>
