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
        $task_table = $processedEmail."_task";  
		
		//When user click Mark As Done
		if(isset($_POST["submitStatus"])){
            $statusValue = $_POST["status"];
            $task_id = $_POST["taskid"];

            //SQL for changing status
            $sqlChangeStatus = "UPDATE $task_table SET is_solved = $statusValue WHERE task_id = $task_id";
            $changeStatusQuery = $mysqli->query($sqlChangeStatus);
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
    
    <?php
        //Code for show Menu Selected 
        $isTasksActive = "active";
        $isAccountActive = "";
        $isViewContactActive = "";
    ?>
    <!-- navigation code goes here -->
    <?php include("menu_navigation.php"); ?>
    <br>
    <center>
    <a class="btn btn-success btn-md" href="tasks.php" style="width:150px;background-color:#127E92;border-color:#127E92;">View All Tasks</a>
    <a class="btn btn-success btn-md" href="create_tasks.php" style="width:150px;">Create New Task</a>
    </center> 
	<br>

    <!-- Task Lists -->
    <?php 
        $sqlViewTasks = "SELECT * FROM $task_table ORDER BY due_date ASC";
        $sqlViewTaskQuery = $mysqli->query($sqlViewTasks);

        $panelColor="";

        //returns a color based on Task Status
        function isTaskDone($isDone){
            if($isDone == 1){   
                return "#E7FFE6";   //Green color
            }else{
                return "#F6FAD5";   //Light yellow color
            }
        }
    ?>

    
    <?php 
        while($row = $sqlViewTaskQuery->fetch_assoc()){
            $color = isTaskDone($row["is_solved"]);   //changing the panel color based 
			
			$status = ($row["is_solved"] == 1)? "Done" : "Pending";
			/*
			if($row["is_solved"] == 1)
				$status = "Done";
			else
				$status = "Pending";  
			*/
			
            echo "<div class='task' style='background-color:$color'>";
            echo "<p><b>Task Title: </b>". $row["task_title"]." </p>";
            echo "<p><b>Due Date: </b>".$row["due_date"]."</p>";
			echo "<p><b>Status: </b>".$status."</p>";
            echo "<p><b>Task Description: </b>".$row["task_description"]."</p>";
			echo "<a class=\"btn btn-success\" style=\"width:100px;height:20px;margin-top:1px;margin-left:10px;font-size:14px;\" href=\"task_details?taskid=".$row["task_id"]."\" >More Actions</a>";
            //echo "<div id=\"button\">";
            echo "<form method='post'>";
            echo "<input type=\"hidden\" value=\"".$row["task_id"]."\" name=\"taskid\">";
            echo "<input type=\"hidden\" value=\"1\" name=\"status\">";
            echo "<input class=\"btn btn-success\" style=\"float:left;margin-top:1px;margin-left:10px;width:100px;font-size:14px;\" type=\"submit\" value=\"Mark As Done\" name=\"submitStatus\">";
            echo "</form>";
            echo "<br>";
            echo "</div>";
        }
        //When there is no task
        if($mysqli->affected_rows == 0){
            echo "<br><br><div class=\"alert alert-danger\" align=\"center\">
                             You have no task!
                        </div>";
        }
    ?>
    <!-- Task List Ends -->


<br><br>

    <!-- Footer code goes here -->
     <?php include("footer.php"); ?>
