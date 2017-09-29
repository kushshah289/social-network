<?php
include "db_connection.php";
$q = $_REQUEST["q"];
$location=[];
$query=" select loc_name from location where loc_name like '%$q%' ";
$result=mysqli_query($con,$query);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $location[] = $row;
    }
}
echo json_encode($location);
?>