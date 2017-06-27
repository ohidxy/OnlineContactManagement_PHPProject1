<?php
    require_once("includes/connect.php");   //Database connection


    //Retrieving Session duration from user table
    $sql_session_duration = "SELECT session_duration ";
    $sql_session_duration.= "FROM user WHERE email = '".$_SESSION['email']."'";

    $sql_session_query = $mysqli->query($sql_session_duration);

    $session_duration="";  //variable initialization

    while($row = $sql_session_query->fetch_assoc()){
        $session_duration = $row['session_duration'];
    }

    //Session Time Out
    if(isset($_SESSION['last_activity'])){
        if((time()-$_SESSION['last_activity'])>($session_duration * 60)){     
            $_SESSION['logged_in'] = false;
            session_unset();
            session_destroy();
            header("location:index.php");
            ob_end_flush();
            exit;
        }
        $_SESSION['last_activity'] = time();
    }else{
        $_SESSION['last_activity'] = time();
    }
?>