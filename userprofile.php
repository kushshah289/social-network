<?php 
include "db_connection.php";
?>
<!DOCTYPE html>
<html>
<head>
</head>
<style>
 body {
	 background-image: url("https://i2.wp.com/walldiskpaper.com/wp-content/uploads/2014/11/Blue-Background-High-Quality.jpg");	
}
</style>
<body>

<?php

$query="select * from profile where username='$_POST[uname]' ";
$result=mysqli_query($con,$query);
if(mysqli_num_rows($result)>0)
{
	echo " This username already exists . Please Try again ";

}
else
{
	$file = $_FILES['file']['name'];
	$file_loc = $_FILES['file']['tmp_name'];
	$file_size = $_FILES['file']['size'];
	$file_type = $_FILES['file']['type'];
	$folder="uploads/";
	move_uploaded_file($file_loc,'$folder'.$file);
	$target_file = $file . basename($_FILES["file"]["name"]);
	echo pathinfo($target_file,PATHINFO_EXTENSION);
	
	$sql = "INSERT into profile (firstname, lastname, birthdate, gender, username, email, password, picture)
	VALUES ('$_POST[firstname]','$_POST[lastname]','$_POST[dob]','$_POST[gender]','$_POST[uname]','$_POST[email]','$_POST[password]','$file') ";
	if (mysqli_query($con, $sql)) {
	    echo "User Profile has been created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . mysqli_error($con);
	}
	mysqli_close($con);
}

?>

<form action='login.php' method=''>
		Click here to Login Now :<input type='submit' name= "login" value="Login Now">
</form>


</body>
</html>

