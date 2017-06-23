<?php
    //Session Time Out
    if(isset($_SESSION['last_activity'])){
        if((time()-$_SESSION['last_activity'])>(10*60)){     //Limiting to 10 minutes
            $_SESSION['logged_in'] = false;
            session_unset();
            session_destroy();
            ob_start();
            header("location:index.php");
            ob_end_flush(); 
            exit();
        }
        $_SESSION['last_activity'] = time();
    }else{
        $_SESSION['last_activity'] = time();
    }
?>