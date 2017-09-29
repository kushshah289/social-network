<?php
$con=mysqli_connect('localhost','root','root','project_dbs');
if (!$con){
	die(" Connection Failed: ". mysqli_connect_error());
}

/*function get_array($arr)
{
	$result=[];
	while($x = mysqli_fetch_assoc($arr))
	{
		$result[] = $x;
	}
	return $result;
}*/
?>