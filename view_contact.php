<?php 
    session_start();
    require_once("includes/session_timeout.php");   //Session Time Out
    require_once("includes/connect.php");   //Database connection
    require_once("includes/session_management.php");   //Session Settings

    
    if(!isset($_SESSION["email"])){  //Checks if a session is started
        //Redirects to login page if a session isn't started
        header("Location:index.php");   
        exit;
    }else{
        after_successful_login();
    }

    $processedEmail = $_SESSION["email"];
    $processedEmail = str_replace("@","",$processedEmail);
    $processedEmail = str_replace(".","",$processedEmail);
    $skillFieldTable = $processedEmail."_skill";
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
<div class="container" style=" border-style: solid;
    border-width: 3px; border-radius:10px; margin-top:10px;">
    <br>
    <p id="test"><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
  <ul class="nav nav-pills nav-justified">
    <li class="active"><a href="view_contact.php">View Contact</a></li>
    <li><a href="create_contact.php">Create New Contact</a></li>
    <li><a href="skill_fields.php">Skill Fields</a></li>
    <li><a href="logout.php">Log Out</a></li>
  </ul>
    
    <!-- Feedback and Bug Report Buttons -->
<feedback style="margin-top:5px; float:right;">
    <a class="btn btn-warning btn-sm" href="updates.html" target="_blank">Updates</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/dkvJLzxftGfC1AIG3" target="_blank">Bug Report</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/w9zM6ECw5qtLKiXH3" target="_blank">Feedback</a>
</feedback>  
    <br><br><br>
    
	
	

	

  <!-- Filter Option for Data Field starts-->
  <p><b>Filtered By Skill Field:</b></p>
  <?php 
            $sql2 = "SELECT * from $skillFieldTable ORDER BY skill_field_name ASC";
            $result2 = $mysqli->query($sql2); 
 
		   
            
            echo "<select id=\"sel\" name=\"selected\" onchange=\"filter()\">";
			echo "<option></option>";
            echo "<option value=\"None\">None</option>";
            while($row = $result2->fetch_assoc()){
                print("<option value='".$row["skill_field_name"]."'>".$row['skill_field_name']."</option>");
            }
            echo "<\select>";
            
    ?>  
    
    
 
 
 <!-- Search by name option for Data Field starts-->
    
    <script>
    //function for filtering contacts
    function filter(){
        var filter, table, tr, td, i, index,innerHTML;
        var e = document.getElementById("sel");     
        var strUser = e.options[e.selectedIndex].value;
        filter = strUser.toUpperCase();
        
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");
      
      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[4];   //Search for column index 1
        if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        } 
      }
    }    
    //function for searching by name
    function myFunction() {
      // Declare variables 
      var input, filter, table, tr, td, i, index,innerHTML;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");
      
      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];   //Search for column index 1
        if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        } 
      }
    }
    </script>
  <!--Search Box -->  
  <input style="float:right;" type="text" id="myInput" onkeyup="myFunction()" name="search" placeholder="Search by name..">  

    <!---------------------------------- -->
    
  
    <!-- View Contact Data Table Starts -->
  <div class="table-responsive"><br/>           
  <table id="myTable" class="table" align="center" id="myTable">
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
		$sqlC = "SELECT * FROM $processedEmail ORDER BY first_name ASC";
	    $querySQLC = $mysqli->query($sqlC);
		
		
		while($row = $querySQLC->fetch_assoc()){
			$html = "<tr>";
			$html .="<td>-</td>";
			$html .="<td>".htmlspecialchars($row["first_name"])." ".htmlspecialchars($row["last_name"])."</td>";
			$html .="<td>".htmlspecialchars($row["email"])."</td>";
			$html .="<td>".htmlspecialchars($row["hp_no"])."</td>";
			$html .="<td>".htmlspecialchars($row["skill_field"])."</td>";
			
			$html .='<td>
                      <a href="contact_details.php?emailid='.htmlspecialchars($row["email"]).'"  class="btn btn-info" style="background-color:#007DA5;">More</a>
                      </td>';
		  $html .="</tr>";
			
			print($html);
		}
        //When table is empty
        while($mysqli->affected_rows === 0){
            $html = "<tr>";
            $html .="<td></td>";
            $html .="<td></td>";
            $html .="<td></td>";
			$html .="<td>There is no record!</td>";
            $html .= "</tr>";
			print($html);
            break;
        }
	  ?>
	  <!-- PHP Code for showing row data ends -->
    </tbody>
  </table>
  </div> 
    <!-- View Contact Data Table Ends --> 
  <br><br><br>
<center>Copyright © 2017 ohid.info</center>   <br>
</div>
    <br><br>
</body>
</html>
