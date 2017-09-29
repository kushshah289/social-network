<?php
include 'db_connection.php';
include  'header.php';
$query="select * from messages where receiver=$_SESSION[user_id]";

$result=mysqli_query($con,$query);
if(mysqli_num_rows($result)>0)
{	
	while($row=mysqli_fetch_assoc($result))
	{	
		
		$query2="select * from profile where user_id=$row[sender]";
		$result2=mysqli_query($con,$query2);
		while($row1=mysqli_fetch_assoc($result2))
		{	
			echo "<a href='friendsprofile.php?id=".$row1["user_id"]."' >"."<h3>".$row1["firstname"]. " ". $row1["lastname"]."</h3>"."</a>";
			echo "<div style=font-weight:bold><h5><strong>".$row["message"]."</strong></h5></div>";
			
			echo "<form method=post >
			<input type='hidden' name='message_id' value=".$row["message_id"].">
			<input type='hidden' name='sender' value=".$row["sender"].">
			<input type='hidden' name='receiver' value=".$row["receiver"].">
			<input type=text name=reply_message>
			<input type=submit name=reply value=Reply>
			<input type=submit name=end_conv value=Delete>
			</form> ";
			
		}	
	}
	
}

?>	


<?php
if(isset($_POST["reply"]))
{
	echo "$_POST[message_id]";
	echo "$_POST[sender]" ;
	echo "$_POST[receiver]";

	$query="update messages set message='$_POST[reply_message]' ,sender='$_POST[receiver]', receiver='$_POST[sender]' where message_id=$_POST[message_id]";
	$result=mysqli_query($con,$query);
	if($result)
	{
		header("Refresh:0");
	}
}

else if(isset($_POST["end_conv"]))
{
	$query="delete from messages where  message_id=$_POST[message_id]";
	$result=mysqli_query($con,$query);
	if($result)
	{
		header("Refresh:0");
	}
}

?>



