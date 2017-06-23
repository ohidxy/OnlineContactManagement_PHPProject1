<?php 


    //Has too much passed since last login?
    function last_login_is_recent(){
        $max_elapsed = 60 * 60 * 24;
        if(!isset($_SESSION['last_login'])){
            return false;
        }
        if(($_SESSION['last_login']+$max_elapsed)>=time()){
            return true;
        }else{
            return false;
        }
    }


    //is session valied?
    function is_session_valid(){
        $check_ip = true;
        $check_user_agent = true;
        $check_last_login = true;
        
        //IP Check: Session VS server
        if($_SESSION['ip'] != $_SERVER["REMOTE_ADDR"]){
            return false;
        }

        
        if($_SESSION['user_agent'] != $_SERVER["HTTP_USER_AGENT"]){
            return false;
        }
        
        if($check_last_login && !last_login_is_recent()){
            return false;
        }
        
        return true;
    }

    //if session is not valid, end and redirect to login page
    function confirm_session_is_valid(){
        if(!is_session_valid()){
            session_unset();
            session_destroy();
            ob_start();
            header("Location:index.php");
            ob_end_flush(); 
            exit;
        }
    }
        
    function is_logged_in(){
        return ((isset($_SESSION['logged_in'])) && $_SESSION['logged_in']);
    }

    function confirm_user_logged_in(){
        if(!is_logged_in()){
            session_unset();
            session_destroy();
            ob_start();
            header("Location:index.php");
            ob_end_flush(); 
            exit;
        }
    }

    function after_successful_login(){
        session_regenerate_id();
        $_SESSION['logged_in'] = true;
        
        //Save these values in the session, even when checks aren't enabled
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_login'] = time();
    }

    function after_successful_logout(){
        $_SESSION['logged_in'] = false;
        
        session_unset();
        session_destroy();
        ob_start();
        header("Location:index.php");
        ob_end_flush(); 
        exit;
    }

    function before_every_protected_page(){
        confirm_user_logged_in();
        confirm_session_is_valid();
    }
?>