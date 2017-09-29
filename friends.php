<!DOCTYPE html>
<html>
<head>

</html>
</head>
<body>
<?php

include 'header.php';
include 'db_connection.php';
$query="call user_friends($_SESSION[user_id])";
$result = mysqli_query($con,$query);
if (mysqli_num_rows($result) > 0) {
    /*echo "<h2> Friend List </h2> ";*/
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class= f_list>";

        //echo $row["firstname"]. " ". $row["lastname"]."<br>";
        echo "<a href='friendsprofile.php?id=".$row["user_id"]."' >"."<h3>".$row["firstname"]. " ". $row["lastname"]."</h3>"."</a>";
        $picture=$row['picture'];

        echo "<img src=uploads/$picture width=150 height=135 />"."<br>";

        echo "</div>"; 

    }
} 
else
{
    echo "No Friends";
}
?>
</body>