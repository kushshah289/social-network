<?php
include 'db_connection.php';
if(isset($_POST["signup"]))
{
    header('Location: http://localhost/project/registration.php');
}
mysqli_close($con);
?>

<!DOCTYPE HTML> 
<html>  
<head>
	
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<style>
 body {
	 background-image: url("https://previews.123rf.com/images/schrades/schrades1306/schrades130600004/20299917-A-warm-toned-off-white-paper-background-with-a-finely-textured-swirling-thread-texture-visible-at-10-Stock-Photo.jpg");	
}
</style>

<body style="text-align: center">
	<h1 > Welcome to Sports Fan Social Network </h1>
<form style="font-weight:bold;" method="post">
	Username:<input type="text" name="uname" placeholder='UserName'><br><br>
	Password:<input type="password" name="password" placeholder='Password'><br><br>
	<input type="submit" name='signin' value='Sign In'> <br><br>
	
</form>

<form style="font-weight:bold;" method="post">
	New User<br><input type="submit" name='signup' value='Sign Up'> <br><br>
</form>

<?php
session_start();
include 'db_connection.php';
if(isset($_POST["signin"]))
{
$query="SELECT *
  FROM profile
 WHERE username = '$_POST[uname]' and 
       password = '$_POST[password]' ";

$result = mysqli_query($con,$query);
$row = mysqli_fetch_assoc($result);
//var_dump($row);
if(mysqli_num_rows($result)>0)
{
	$login="call set_login_time('$row[username]')";
	mysqli_query($con,$login);
	$_SESSION['user_id'] = $row['user_id'];
	$_SESSION['firstname'] = $row['firstname'];
	$_SESSION['lastname'] = $row['lastname'];
	$_SESSION['username'] = $row['username'];
	$_SESSION['email'] = $row['email'];
	$_SESSION['password']=$row['password'];


    $_SESSION['authen']=True;
    
    header('Location: http://localhost/project/home.php');
}
else
{
	echo "<strong>Username or Password is Incorrect</strong>";
}
mysqli_close($con);
}
?>







