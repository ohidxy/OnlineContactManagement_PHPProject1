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

        if(isset($_POST["createTask"])){
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

                $sqlCreateTask = "INSERT INTO $task_table ";
                $sqlCreateTask.= "(task_title, due_date, task_description, is_solved) ";
                $sqlCreateTask.= "VALUES (?, ?, ?, 0) ";

                $stmt = $mysqli->prepare($sqlCreateTask);
                $stmt->bind_param("sss", $task_title, $due_date, $task_description);
                $stmt->execute();
                $stmt->close();

                $addSuccess = true;
            }
        }

        /*if(isset($_POST["deleteTask"])){
            if(Token::checkToken($_POST["csrf_token"])){
                $sqlDeleteTask = "DELETE * FROM $task_table"
            }
        }*/
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
    <a class="btn btn-success btn-md" href="tasks.php" style="width:150px;">View All Tasks</a>
    <a class="btn btn-success btn-md" href="create_tasks.php" style="width:150px;background-color:#127E92;border-color:#127E92;">Create New Task</a>
    </center> 


    <!--************************************* Create Tasks Form *********************-->
    <center><br>
    <h1>Create a New Task</h1>
    <form style="width:550px;margin-left:20px;" method="post" align="left">
        <!-- Code for avoiding data duplication -->
        <input type="hidden" name="csrf_token" value="<?php echo Token::generateToken(); //Generating the Token  ?>">    

        Task Title:<br>
        <input style="width:440px;" placeholder="Maximum 200 Characters" type="text" name="tasktitle" value ="" maxlength="200" required><br>

        Due Date:<br>
        <input placeholder="Due Date" type="date" name="duedate" value ="" required><br>

        Task Description:<br>
        <textarea name="taskdescription" maxlength="1000">
        
        </textarea>  

        <br>
        <!--PHP COde for Selecting Categories -->
        
        <?php 
            
        ?>
        <input class="btn btn-success" style="width:100px;height:30px;float:right;margin-top:0px;margin-right:105px;width:100px;font-size:18px;" type="submit" value="Submit" name="createTask">
		<br><br>
        <?php  //Success Message
            if(isset($addSuccess)){
                if($addSuccess){
                    echo "<div class=\"alert alert-success\" align=\"center\">
                            <strong>Success!</strong> You have added a new Task!
                        </div>";
                }
            }
        ?>
		
		
	</form>
    </center>
    <!-- ********************************************************* -->


    <!-- Footer code goes here -->
     <?php include("footer.php"); ?>
