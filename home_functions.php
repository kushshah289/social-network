<?php
include "db_connection.php";
session_start();

$table_names = [];
$comments = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    	if($_POST['OPERATION'] == 'get_diaries'){
            
            $sql1 = "select concat(profile.firstname,' ', profile.lastname) as Name, diary.post_id, DATE(diary.created_date) AS cr_date, diary.user_id,diary.diary_title,diary.description,diary.created_date from diary inner join profile on profile.user_id= diary.user_id where diary.user_id in (SELECT  profile.user_id FROM profile 
WHERE profile.user_id IN 
(SELECT friends.userid_1 as ids FROM friends WHERE friends.userid_2 =$_SESSION[user_id] and friends.action=1
UNION SELECT friends.userid_2 as ids FROM friends WHERE friends.userid_1 =$_SESSION[user_id] and friends.action=1 )) and diary.Privacy_D IN (1,2) order by diary.created_date desc"; 
			


			echo $sql1;
            $result1 = $con->query($sql1);
            if ($result1->num_rows > 0) {
            // output data of each row
            while($row = $result1->fetch_assoc()) {
                $table_names[]=$row;
            }
        } else {
            echo "0 results";
        }
        echo json_encode($table_names);
        //echo $table_names;
    }

    else if($_POST['OPERATION'] == 'get_comments')
    {
    	$comm = "select * from comments";
       $result2= $con->query($comm);
       if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $comments[]=$row;
            }
        }
        echo json_encode($comments);
    }

    else if($_POST['OPERATION'] == 'get_comments_new')
    {
        $comm = "select * from comments inner join profile on profile.user_id=comments.commentor where post_id = $_POST[id] ORDER by comments.comment_id desc";
       $result2= $con->query($comm);
       if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $comments[]=$row;
            }
        }
        echo json_encode($comments);
    }

    else if($_POST['OPERATION'] == 'add_new_comment')
    {
        $comm = "INSERT INTO comments(post_id, commentor, Comment, Status_c) VALUES ($_POST[post_id], $_SESSION[user_id], '$_POST[description]', 1)";
        //echo $comm;
        $result2= $con->query($comm);
    }

    else if($_POST['OPERATION'] == 'delete_comment')
    {
        $comm = "UPDATE comments SET Status_c = 0 WHERE comment_id = $_POST[comment_id]";
        //echo $comm;
        $result2= $con->query($comm);
    }

    else if($_POST['OPERATION'] == 'delete_post')
    {
        $comm = "UPDATE diary SET Status_d = 0 WHERE post_id = $_POST[post_id]";
        //echo $comm;
        $result2= $con->query($comm);
    }

    else if($_POST['OPERATION'] == 'get_likes_by_post')
    {
        $comm = "SELECT COUNT(*) as count_likes From likes where post_id=$_POST[post_id]";
        //echo $comm;
        $x=[];
        $result2= $con->query($comm);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $x=$row;
            }
        }
        echo json_encode($x);
    }

    else if($_POST['OPERATION'] == 'has_user_liked')
    {
        $comm = "SELECT COUNT(*) as has_liked FROM likes WHERE post_id=$_POST[post_id] and liker=$_SESSION[user_id]";
        //echo $comm;
        $x=[];
        $result2= $con->query($comm);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $x=$row;
            }
        }
        echo json_encode($x);
    }

    else if($_POST['OPERATION'] == 'change_likes')
    {
        if($_POST['current_value'] == '0'){
            $comm = "INSERT into likes(post_id, liker) VALUES ($_POST[post_id], $_SESSION[user_id])";    
        }
        else if($_POST['current_value'] == '1'){
            $comm = "delete from likes where post_id = ".(int)$_POST[post_id]." and liker = ".(int)$_SESSION[user_id]."";   
        }
        else{
            return false;
        }
          
         //$result2= $con->query($comm);
         mysqli_query($con,$comm);
    }

    else if($_POST['OPERATION'] == 'get_friend_privacy')
    {
        $comm = "SELECT privacy_P from profile where user_id = $_POST[id]";
        //echo $comm;
        $x=[];
        $result2= $con->query($comm);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $x=$row;
            }
        }

        //$x= json_encode($x);
        //echo $x['privacy_P'];
$show_hide = "hide";
        if($x['privacy_P'] == 0){
            $show_hide = "show";
        }

        // privacy for friends
        elseif ($x['privacy_P'] == 1) {
            //echo "friends";
            $z = [];
            $sql = "SELECT  profile.user_id FROM profile 
                    WHERE profile.user_id IN 
                    (SELECT friends.userid_1 as ids FROM friends WHERE friends.userid_2 = $_POST[id] and friends.action=1
                    UNION SELECT friends.userid_2 as ids FROM friends WHERE friends.userid_1 = $_POST[id] and friends.action=1 )";
            
            $result2= $con->query($sql);
            if ($result2->num_rows > 0) {
                // output data of each row
                while($row = $result2->fetch_assoc()) {
                    $z[]=$row;
                }
            }
            //echo json_encode($z);
            
            foreach ($z as $value) {
                //echo $value['user_id'];
                if($_SESSION['user_id'] == $value['user_id']){
                    $show_hide = "show";
                    break;
                }
            }       
        }

         // privacy for frinds of friends
        elseif ($x['privacy_P']==2) {
            $sql="call fof_privacy($_POST[id]);";
            $result2= $con->query($sql);
            if ($result2->num_rows > 0) {
                // output data of each row
                while($row = $result2->fetch_assoc()) {
                    $z[]=$row['user_id'];
                }
            }
            //echo json_encode($z);
            foreach ($z as $value) {
                //echo $value['user_id'];
                if($_SESSION['user_id'] == $value){
                    $show_hide = "show";
                    break;
                }
            } 

        }


        else{}

        $y['show_hide'] = $show_hide;    
    echo json_encode($y);

    }
    else{}
}
?>

