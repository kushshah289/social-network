<?php
session_start();
	include 'db_connection.php';
    $sql="call set_logout_time('$_SESSION[username]')";
    $res=mysqli_query($con,$sql);
    if($res){
    	unset($_SESSION['user_id']);
		unset($_SESSION['firstname']);
		unset($_SESSION['username']);
		unset($_SESSION['lastname']);
		unset($_SESSION['email']);
		unset($_SESSION['authen']);
		session_destroy();
		header('Location: http://localhost/project/login.php');
    }
  ?>
