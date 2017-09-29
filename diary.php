<?php
include 'header.php';
include 'db_connection.php';
?>

<html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>
function showHint(str) {
    if (str.length == 0) { 
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        var html = "";
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                //document.getElementById("txtHint").innerHTML = data['loc_name'];
                var len=0;
                for(var key in data)
                {
                    len ++;
                    //document.getElementById("txtHint").innerHTML = data[key]['loc_name'];
                    console.log(data[key]['loc_name']);
                    html += "<option value='"+data[key]['loc_name']+"'>"+data[key]['loc_name']+"</option>";
                }
                
            }
            $('#dropdown').html(html);
            $('#dropdown').attr("size", len); // open dropdown

        };

        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>
<style>
.container
{

}
</style>
</head>
<body>


<?php 

if(!$_SESSION['username']){header('Location: http://localhost/project/login.php');} 

//echo $_SESSION['user_id']; 
?>
<!DOCTYPE HTML>
<html>
<head>
</head>
<style>
#button {
     
     position:absolute;
     top:0;
     right:0;
 }

</style>
<body style="text-align: center" >



<?php
//session_start();
$user_id=$diary_title=$description=$privacy_D='';


if(isset($_POST["done"]))
{

  if(true){
    //echo "asasa";
  }

  if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
   $sql = "INSERT into diary (user_id, diary_title, description, privacy_D,location,multimedia) VALUES ($_SESSION[user_id],'$_POST[diary_title]','$_POST[description]',$_POST[privacyd],'$_POST[location]','')";
    if (mysqli_query($con, $sql)) {
        echo "Diary created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}else{

  if (($_FILES["file"]["type"] == "video/mp4")
|| ($_FILES["file"]["type"] == "audio/mp3")
|| ($_FILES["file"]["type"] == "audio/wma")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg"))
  {
    $file = $_FILES['file']['name'];
    $file_loc = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    
    $folder="uploads/";
    $target_file = $folder . basename($_FILES["file"]["name"]);
    echo pathinfo($target_file,PATHINFO_EXTENSION);
    
    
    move_uploaded_file($file_loc,$folder.$file);
    $sql = "INSERT into diary (user_id, diary_title, description, privacy_D,location,multimedia) VALUES ($_SESSION[user_id],'$_POST[diary_title]','$_POST[description]',$_POST[privacyd],'$_POST[location]', '$file')";
    if (mysqli_query($con, $sql)) {
        echo "Diary created successfully";
        //echo "adasafasfdfdsfsd";
        //echo $_FILES["file"]["type"];
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
        echo "Only jpg,jpeg,mp4 files supported";
    }
  }else {
        //echo "Error: " . $sql . "<br>" . mysqli_error($con);
        echo "Only jpg,mp4,png files supported";
    }
  }
}



?>

<?php

if(isset($_POST["done"]))
{

  $location=$_POST['location'];
  $query="select loc_name from location where loc_name like '%$location%' ";
  $result=mysqli_query($con,$query);
  if( mysqli_num_rows($result) <= 0 )
  {
    $query2=" insert into location(loc_name) values ('$location')";
    mysqli_query($con,$query2);
  }
}
?>



<div container='fluid'>

<h2>New Diary</h2> 
<div style="font-weight:bold">
   
<form action='#' method='post' enctype="multipart/form-data"> 
<input type="text" name="diary_title" placeholder='Title' required> 
     <br><br>
<input type="text" name="description" placeholder='Description' required> 
    <br> <br>

  
  <select name='privacyd' >
  <option value='0'>Eveyone</option>
  <option value='1'>Only Friends</option>
  <option value='2'>Friends of Friends</option>
  <option value='3'>Private</option>
</select>
<br><br>
<input type="text" list='dropdown' placeholder='Location' onkeyup="showHint(this.value)" name='location'><br>
<datalist id='dropdown'>

</datalist>

<br>Attach a File:<input style="padding-left:590px" type="file" name="file" value='Upload Picture' />

<br>

<input type="submit" name="done" value="Done" >
<input type="reset" name ="reset" value="Reset" ><br><br>


</form>

</div>  