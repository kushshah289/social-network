<?php include "header.php"; 
include "db_connection.php";
 
$comm = "select * from comments inner join profile on profile.user_id=comments.commentor WHERE comments.Status_c=1 ORDER by comments.comment_id";
       $result2= $con->query($comm);
       if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $comments[]=$row;
            }
        }
        $sql1 = "select concat(profile.firstname,' ', profile.lastname) as Name, diary.post_id, date_format(diary.created_date,'%M %D,%Y') AS cr_date, diary.user_id,diary.diary_title,diary.description,diary.multimedia,diary.created_date,diary.location, profile.picture AS picture from diary inner join profile on profile.user_id= diary.user_id where diary.user_id = $_GET[id] and diary.status_d=1 and privacy_P not in(3)  order by diary.created_date desc"; 
            
            $result1 = $con->query($sql1);
            if ($result1->num_rows > 0) {
            // output data of each row
            while($row = $result1->fetch_assoc()) {
                $table_names[]=$row;
            }
        } else {
            //echo "0 results";
        }

?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">

{
    var elem = document.getElementById("#mybutton");
    if (elem.value=="Send Request")  elem.value = "Cancel Request";
    else if (elem.value==" Cancel Request") elem.value='Send Request';
    else if (elem.value==" Unfriend") elem.value='Send Request';

    
}
</script>
	<style>

table, td, th {    
    border: 1px solid #ddd;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 15px;
}
div.f_profile
{
    font-weight:bold;
}
.remove-post{
    color:red;
}
.green{
    color:green;
}
.red{
    color:red;
}.name{
    width:20%;
    float: left;
}
.title{
    float: left;
    width:80%;
}
.main-container{
    border-width: 1px;
    border:solid;
    padding-top: 0px;
    margin-top: 10px;
    background-color: #f9ecf2;
    margin-left: 100px;
}
.comment-container-inside{
    background-color:  #f0f0f5;
    margin-top: 10px;
}
.glyphicon-remove{
    float:right;
}

</style>

</head>
<style type="text/css"></style>
<script type="text/javascript">


$(document).on("click", ".add-comment-button", function(){
            //alert('sasa');
            var url = "home_functions.php";
            var post_id = $(this).attr('post_val');
            //alert(post_id+"---"+$('#add_comment_'+post_id).val());
            var desc = $('#add_comment_'+post_id).val();
            formDataComments = {'OPERATION':'add_new_comment','post_id':post_id,'description':desc};
            $.ajax({
                        type:"POST",
                        url : url,
                        data: formDataComments,
                        success:function(data)
                        {
                            //alert(data)
                            var commentHtml = "<div class='comment-container-inside'><span class='name'><?php echo $_SESSION['firstname']." ".$_SESSION['lastname']; ?></span><span>" +desc+"</span><span comment_id='"+""+"' class='glyphicon glyphicon-remove'></span></div>";

                            $('#comment-container-'+post_id).append(commentHtml);       
                        }
                    })
        });

$(document).on('click', '.glyphicon-remove', function(){
    //alert($(this).attr('comment_id'));
    $(this).parent().hide();
    var url = "home_functions.php";
            formDataComments = {'OPERATION':'delete_comment','comment_id':$(this).attr('comment_id')};
            $.ajax({
                        type:"POST",
                        url : url,
                        data: formDataComments,
                        success:function(data)
                        {
                            $(this).parent().hide();        
                        }
                    })
});

function get_likes_by_post(post_id){
    var x =post_id;
    var url = "home_functions.php";
            formDataComments = {'OPERATION':'get_likes_by_post','post_id':post_id};
            $.ajax({
                        type:"POST",
                        url : url,
                        data: formDataComments,
                        success:function(data)
                        {
                            var data = JSON.parse(data);
                            $('#like_block_'+x).html("<span id='like_count_value_"+x+"' value='"+data['count_likes']+"'>"+data['count_likes']+"</span>");

                        }
                    })
}

function has_user_liked(post_id){
    var x =post_id;
    var url = "home_functions.php";
            formDataComments = {'OPERATION':'has_user_liked','post_id':post_id};
            $.ajax({
                        type:"POST",
                        url : url,
                        data: formDataComments,
                        success:function(data)
                        {
                            var data = JSON.parse(data);
                            //alert(data['has_liked']);
                            if(data['has_liked']==0){
                                $('#like_symbol_'+x).html("  <span post_id='"+x+"' value='"+data['has_liked']+"' class='glyphicon glyphicon-thumbs-up green like-icon'><span>");
                            }
                            else if(data['has_liked']==1){
                                $('#like_symbol_'+x).html("  <span post_id='"+x+"' value='"+data['has_liked']+"' class='glyphicon glyphicon-thumbs-down red like-icon'><span>");
                            }
                            else{}
                        }
                    })
}

$(document).on('click','.like-icon', function(){
    var x = $(this).attr("post_id");
    var current_value = $(this).attr('value');
    var xyz = $("#like_count_value_"+x).attr('value');
    //alert($(this).attr('post_id')+"--"+$(this).attr('value'));

    var url = "home_functions.php";
            formDataComments = {'OPERATION':'change_likes','post_id':x,'current_value':current_value};
            $.ajax({
                        type:"POST",
                        url : url,
                        data: formDataComments,
                        success:function()
                        {
                            if(current_value == 1){
                                $('#like_symbol_'+x).html("  <span post_id='"+x+"' value='0' class='glyphicon glyphicon-thumbs-up green like-icon'><span>");
                                var count = parseInt(xyz);
                                count = count-1;
                                $("#like_block_"+x).html("<span id='like_count_value_"+x+"' value='"+count+"'>"+count+"</span>");
                            }
                            else if(current_value == 0){
                            $('#like_symbol_'+x).html("  <span post_id='"+x+"' value='1' class='glyphicon glyphicon-thumbs-down red like-icon'><span>");
                                var count = parseInt(xyz);
                                count = count+1;
                                $("#like_block_"+x).html("<span id='like_count_value_"+x+"' value='"+count+"'>"+count+"</span>");
                            }
                            else{}

                        }
                    })

})
        
    function get_friend_privacy(friend_user_id){
    var url = "home_functions.php";
    var data_;
            formDataComments = {'OPERATION':'get_friend_privacy','id':friend_user_id};
            $.ajax({
                        type:"POST",
                        url : url,
                        dataType: "text",
                        data: formDataComments,
                        success:function(data)
                        {
                            //alert(data);
                            var abc = JSON.parse(data);
                            //alert(abc['show_hide'])
                            if(abc['show_hide'] === 'show'){
                                
                            }else if(abc['show_hide'] === 'hide'){
                                $('#page-content').hide();
                            }
                            else{}
                        }
                    })
             
    }

    $(document).ready(function(){
        //var comments = get_comments();
        //home_page_diaries();
        //console.log(comments);
        //print_comments(comments);
        //$('#page-content').hide();
        var friend_user_id = <?php echo $_GET['id'] ?>;
        //alert(friend_user_id);

       // alert(friend_privacy);

        //map_comments(comments);
        var current_user = <?php echo $_SESSION['user_id'] ?>;
        //alert(current_user);
        var comments = <?php echo json_encode($comments); ?>;
        for(var key in comments){
            //console.log(comments[key]);
        }
        var diaries = <?php echo json_encode($table_names);?>;
        for(var key in diaries){
            //console.log(diaries[key]['picture']);
        }


        for(var key in diaries){
            //console.log(diaries[key]);
            has_user_liked(diaries[key]['post_id']);
            var html="";
            html += "<div class='main-container' id='post_"+diaries[key]['post_id']+"'><div class='name_title'><div class='name'><a href='friendsprofile.php?id="+diaries[key]['user_id']+"'><h6><strong>"+diaries[key]['Name']+"</strong></h6></a><img src='uploads/"+diaries[key]['picture']+"' width=75 height=75/></div><div class='title'><span><h3>"+diaries[key]['diary_title']+"</h3><h6 class='date'>"+diaries[key]['cr_date']+"</h6><h5>"+(diaries[key]['location']!==""?"<span class='glyphicon glyphicon-map-marker'></span><a href='http://localhost/project/location.php?loc="+diaries[key]['location']+"'<span class='location'>"+diaries[key]['location']+"</span></a>":"")+"</h5><strong></strong></div></div><div class='desc_comment'><div class='description'><h5>"+diaries[key]['description']+"</h5></span>"+(diaries[key]['multimedia']!==''?"<div  style='padding-left:100px;'><img src='uploads/"+diaries[key]['multimedia']+"'width=200 height=200/></div>":"")+"</div><div class='likes_block'>Likes:<span id='like_block_"+diaries[key]['post_id']+"'></span><span class='like_symbol' id='like_symbol_"+diaries[key]['post_id']+"'></span></div><div id='comments_"+diaries[key]['post_id']+"' class='commments'><h6>----comments here----</h6></div></div></div>";
            $("#page-content").append(html);
            get_likes_by_post(diaries[key]['post_id']);
        }
        
        for(var key in diaries){
            var commentHtml = "";
            commentHtml += "<div class='comment-container' id='comment-container-"+diaries[key]['post_id']+"'>";
            for(var key_1 in comments){
                if(diaries[key]['post_id'] == comments[key_1]['post_id']){
                    

                    console.log(comments[key_1]);
                    commentHtml += "<div class='comment-container-inside'><span class='name'><a href='friendsprofile.php?id="+comments[key_1]['commentor']+"'>"+comments[key_1]['firstname']+" "+comments[key_1]['lastname']+"  </span></a><span>" + comments[key_1]['Comment']+"</span>"+(current_user == comments[key_1]['commentor'] ? "<span comment_id='"+comments[key_1]['comment_id']+"' class='glyphicon glyphicon-remove'></span>":"" )+"</div>";
                    commentHtml += "";

                    /*commentHtml += "<div><input id='add_comment_"+comments[key_1]['post_id']+"' post_val='"+comments[key_1]['post_id']+"' type='text' style='width:500px'><input class='add-comment-button' type='button' value='Add Comment' post_val='"+comments[key_1]['post_id']+"'></div>";*/
                }

            }
            commentHtml += "</div><div><input id='add_comment_"+diaries[key]['post_id']+"' post_val='"+diaries[key]['post_id']+"' type='text' style='width:500px'><input class='add-comment-button' type='button' value='Add Comment' post_val='"+diaries[key]['post_id']+"'></div>";
                $("#comments_"+diaries[key]['post_id']).html(commentHtml);
        }

        get_friend_privacy(friend_user_id);


    }); 
</script>
<body>
<!-- Image Display and Action-->
<?php

$friend_id = $_GET["id"];

if ( $friend_id==$_SESSION['user_id'])
{
    header('Location: http://localhost/project/profile.php');
}
include 'db_connection.php';

if(!$_SESSION['username']){header('Location: http://localhost/project/login.php');}


$sql="select action from friends where (userid_1=$_SESSION[user_id] and userid_2=$friend_id) or (userid_1=$friend_id and userid_2=$_SESSION[user_id])";
$result=mysqli_query($con,$sql);
if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_assoc($result))
    {
        $action=$row['action'];
    }
}
else 
    {
        static $action=null;
        }

?>


<!-- Checking Friend Status -->


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if(isset($_POST["Send_Request"]))
    {
        if ( $friend_id > $_SESSION["user_id"])
        {
            $smaller=$_SESSION["user_id"];
            $greater=$friend_id;
        }
        else
        {
            $smaller=$friend_id;
            $greater=$_SESSION["user_id"];
        }
    $query="insert into friends(userid_1,userid_2,action,action_user) values('$smaller','$greater','0','$friend_id')";
    $result=mysqli_query($con,$query);
   
    }
   

    else if(isset($_POST["Cancel_Request"]))
    {
        if ( $friend_id > $_SESSION["user_id"])
        {
            $smaller=$_SESSION["user_id"];
            $greater=$friend_id;
        }
        else{
            $smaller=$friend_id;
            $greater=$_SESSION["user_id"];
        }
    $query="delete from friends where userid_1=$smaller and userid_2=$greater";
    $result=mysqli_query($con,$query);
   
    }
    
    else if(isset($_POST["Unfriend"]))
    {
        if ( $friend_id > $_SESSION["user_id"])
        {
            $smaller=$_SESSION["user_id"];
            $greater=$friend_id;
        }
        else{
            $smaller=$friend_id;
            $greater=$_SESSION["user_id"];
        }
    $query="delete from friends where userid_1=$smaller and userid_2=$greater";
    $result=mysqli_query($con,$query);
   
    }

}
?>
<?php

if(isset($_POST["send_message"]))
{
    $query="insert into messages(sender,receiver,message) values ($_SESSION[user_id],$friend_id,'$_POST[message]')";
    $result=mysqli_query($con,$query);

}
?>

<?php
$query="select picture from profile where user_id=$friend_id";
$result=mysqli_query($con,$query);
if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_assoc($result))
    {
        $picture=$row["picture"];
    }
}
?>



<br><br><br> 
<div style="float:left; width:30%">
    
<img src="uploads/<?php echo $picture; ?>" width="300" height="300" /><br><br>
<form method='post' action=''>
    <input type="submit" name="<?php if ($action==1) { echo 'Unfriend';} else if ($action==null) {echo 'Send_Request';} else if ($action==0) {echo 'Cancel_Request';} ?>" 
                        value="<?php if ($action==1) { echo 'Unfriend';} else if ($action==null) {echo 'Send Request';} else if ($action==0) {echo 'Cancel Request';} ?>" onclick="change()" id="mybutton" >
<?php
if($action==1)
{
    echo "Message: <input type='text' name='message' placeholder='Message'> 
          <input type='submit' name='send_message' value='Send'>"; 
}
?>                      
</form>
</div>

<div id='page-content' style="width:70%; float:right; overflow-y:auto; height:600px;">
        </div>
    



<?php
$query="select firstname,date_format(birthdate,'%D %M, %Y') as birthdate,email,country,lastname,country,fteam,fsport,fplayer,picture from profile where user_id=$friend_id";
$result=mysqli_query($con,$query);
if(mysqli_num_rows($result)>0)
{
    echo "<br>"."<h3 style=text-decoration:underline><strong>About me:  </strong>  </h3> ";
         
    while($row=mysqli_fetch_assoc($result))
    {
        echo "<div class=f_profile>";
        echo "<td>"."Name: ".$row["firstname"].' '.$row["lastname"]."<br>";
        echo "<td>"."Birthday: ".$row["birthdate"]."<br>";
        echo "<td>"."Email: ".$row["email"]."<br>";
        echo "<td>"."Country: ".$row["country"]."<br>";
        echo "<td>"."Sport: ".$row["fsport"]."<br>";
        echo "<td>"."Team: ".$row["fteam"]."<br>";
        echo "<td>"."Player: ".$row["fplayer"]."<br>";
        echo "</div>";

    }
}
?>

<!-- Diplaying Friend List -->

<?php
if($action==1)
{

    $query="SELECT  profile.user_id, profile.firstname, profile.lastname FROM profile 
    WHERE profile.user_id IN 
    (SELECT friends.userid_1 as ids FROM friends WHERE friends.userid_2 =$friend_id and friends.action=1
    UNION SELECT friends.userid_2 as ids FROM friends WHERE friends.userid_1 = $friend_id and friends.action=1 )";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) > 0) {
    	echo "<h3 style=text-decoration:underline><strong> Friends </strong>  </h3> ";
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {

            //echo $row["firstname"]. " ". $row["lastname"]."<br>";
            echo "<a style=font-weight:bold href='friendsprofile.php?id=".$row["user_id"]."' >".$row["firstname"]. " ". $row["lastname"]."</a><br>";

        }
    } else {
        echo "0 results";
    }
}
?>






