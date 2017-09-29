<?php
include 'db_connection.php';
include 'header.php';
$query="select concat(profile.firstname,' ', profile.lastname) as name, user_id,picture from profile where user_id in
		( SELECT profile.user_id FROM profile WHERE profile.user_id IN (SELECT friends.userid_1 as ids FROM friends WHERE 
		friends.userid_2 =$_SESSION[user_id]  and friends.action=0 and action_user=$_SESSION[user_id] 
		UNION SELECT friends.userid_2 as ids FROM friends WHERE 
		friends.userid_1 = $_SESSION[user_id] and friends.action=0 and action_user=$_SESSION[user_id] ))";
$result=mysqli_query($con,$query);
if(mysqli_num_rows($result)>0)
{
	while($row=mysqli_fetch_assoc($result))
	{
		$f_id=$row["user_id"];
		$picture=$row['picture'];

        echo "<img src=uploads/$picture width=150 height=135 />"."<br>";
		echo "<div style='font-weight:bold' class='friend_request' id='friend_request_".$row["user_id"]."' friend_id='".$row["user_id"]."'>
		<a  href='friendsprofile.php?id=".$row["user_id"]."' >"."<h4>".$row["name"]."</h4>"."</a>".
		"<form id='form_".$row["name"]."'  method=post >
		<input type='hidden' name='friend_id' value=".$row["user_id"]."> 
		<input type=submit name=accept value=Accept>
		<input type=submit name=decline value=Decline>
		</form>
	    </div>"."<br>";
	}
}


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>

</script>

<?php
if(isset($_POST["accept"]))
{
	$_POST["friend_id"];

	
	$query=" update friends set action=1 where ((userid_1=$_SESSION[user_id] and userid_2=$_POST[friend_id]) or (userid_2=$_SESSION[user_id] and userid_1=$_POST[friend_id])) and action_user=$_SESSION[user_id]"; 
	$result=mysqli_query($con,$query);
	if($result){
		header("Refresh:0");
		
	}
	
}
else  if(isset($_POST["decline"]))
{
	$_POST["friend_id"];

	
	$query=" delete from  friends  where ((userid_1=$_SESSION[user_id] and userid_2=$_POST[friend_id]) or (userid_2=$_SESSION[user_id] and userid_1=$_POST[friend_id])) and action_user=$_SESSION[user_id]"; 
	$result=mysqli_query($con,$query);
	if($result){
		header("Refresh:0");
		
	}
}
?>





