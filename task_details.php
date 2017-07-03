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
		if(isset($_POST["saveTask"])){
            if(Token::checkToken($_POST["csrf_token"])){
                $task_title = $_POST["tasktitle"];
                $task_title = $mysqli->real_escape_string($task_title);
                $task_title = htmlspecialchars($task_title);
                $task_title = ucwords($task_title);

                $due_date = $_POST["duedate"];
                $due_date = $mysqli->real_escape_string($due_date);
                $due_date = htmlspecialchars($due_date);
                

                $task_description = $_POST["taskdescription"];
                $task_description = $mysqli->real_escape_string($task_description);
                $task_description = htmlspecialchars($task_description);
                $task_description = str_replace('\r\n','', $task_description);

                $task_status = $_POST["statusDropDown"];
                $task_status = $mysqli->real_escape_string($task_status);

                $sqlCreateTask = "UPDATE $task_table ";
                $sqlCreateTask.= "SET task_title = ?, due_date = ?, task_description = ?, is_solved = ? ";
                $sqlCreateTask.= "WHERE task_id =". $_POST["taskid"];

                $stmt = $mysqli->prepare($sqlCreateTask);
                $stmt->bind_param("ssss", $task_title, $due_date, $task_description, $task_status);
                $stmt->execute();
                $stmt->close();

                $saveSuccess = true;
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
    <br><br>
    <center>

    <?php 
        
    ?>

    <?php 
        $sqlViewTaskDetails = "SELECT * from $task_table WHERE task_id = ".$_GET["taskid"];
        $createsqlViewTaskDetails = $mysqli->query($sqlViewTaskDetails);

        while($row = $createsqlViewTaskDetails->fetch_assoc()){
    ?>
    <form style="width:550px;margin-left:20px;" method="post" align="left">
        <!-- Code for avoiding data duplication -->
        <input type="hidden" name="csrf_token" value="<?php echo Token::generateToken(); //Generating the Token  ?>">    

        <input type ="hidden" name="taskid" value="<?php echo $row["task_id"]; ?>">
        Task Title:<br>
        <input style="width:440px;" placeholder="Maximum 200 Characters" type="text" name="tasktitle" value ="<?php echo $row["task_title"]; ?>" maxlength="200" required><br>

        Due Date:<br>
        <input placeholder="Due Date" type="date" name="duedate" value ="<?php echo $row["due_date"]; ?>" required><br>
        Status:<br>
        <select name="statusDropDown">
            <option value ="0" <?php echo $value = ($row["is_solved"]==0)?"selected":""; ?> >Pending</option>
            <option value ="1" <?php echo $value = ($row["is_solved"]==0)?"":"selected";?>>Done</option>
        </select>
        <br>
        Task Description:<br>
        <textarea name="taskdescription" maxlength="1000"><?php echo $row["task_description"]; ?></textarea>  

        
        <?php
            //Finishing of  
            } 
        
        
        ?>
        <br>
        <!--PHP COde for Selecting Categories -->
        <input class="btn btn-success" style="width:70px;height:25px;float:right;margin-top:0px;font-size:15px;margin-right:110px;" type="submit" value="Delete" name="deleteTask">
        <input class="btn btn-success" style="width:100px;height:30px;float:right;margin-top:0px;margin-right:10px;font-size:18px;" type="submit" value="Save" name="saveTask">
		<br><br>
        <?php  //Success Message
            if(isset($saveSuccess)){
                if($saveSuccess){
                    echo "<div class=\"alert alert-success\" align=\"center\">
                            <strong>Success!</strong> You have saved the task!
                        </div>";
                }
            }
        ?>
		
		
	</form>
    </center>


    
	


<br><br>

    <!-- Footer code goes here -->
     <?php include("footer.php"); ?>
