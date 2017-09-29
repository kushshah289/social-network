<?php include 'header.php'; 
include 'db_connection.php'; 
?> 


<?php
$curr_password=$_SESSION['password']; 
if(isset($_POST["submit"])) 
  {   
    $query="update profile set password=$_POST[password] whereuser_id=$_SESSION[user_id]";
  
  $res=mysqli_query($con,$query);
  header('Location: http://localhost/project/profile.php');
}
?>

<!DOCTYPE HTML>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script type="text/javascript">
  $( document ).ready(function() {
    //alert( "ready!" );
    $('#submit_button').on('click',function(){
      
      if($('#old_pass').val()==$('#pass1').val()){
        if( $('#pass2').val() == $('#pass3').val()){
          $('form').submit();
        }
        else{
          alert('Passwords do not match');
          return false;
         }

      }else{
        alert('Old Password do not match');
        return false;

      }

      //alert($('#pass2').val()+"---"+$('#pass3').val())  
      
    })
});
  </script>
</head>

<body>
<form style="text-align:center;font-weight:bold;" id='form' action='' method='post'>
  <input type="hidden" value='<?php echo $curr_password; ?>' id='old_pass'>
  Current Password:<input type="password" name='curr_password' placeholder="Current Password" id="pass1" required><br><br>

  New Password: <input type="password" name='password' id="pass2" placeholder='Password(6 char minimum)' pattern=".{6,}" title="Six or More characters" required><br><br>

  Confirm Password:<input type="password" name='conpassword' id="pass3" placeholder='Confirm Password' pattern=".{6,}" title="Six or More characters" required><br><br>

  <input type="submit" name="submit" value="Change" id='submit_button'>
</form>
</body>