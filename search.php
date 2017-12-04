<?php include "header.php"; ?>
<?php
include 'db_connection.php';
$keyword=$_POST["keyword"];

if ($keyword==null)
{
    
    echo "<h3> Please type in your choice </h3>";

}

else if($_POST['key']=='post')
{
	$query= "";
	$comm = "select * from comments inner join profile on profile.user_id=comments.commentor WHERE comments.Status_c=1 ORDER by comments.comment_id";
       $result2= $con->query($comm);
       if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $comments[]=$row;
            }
        }
        $sql1 = " select  concat(profile.firstname,' ', profile.lastname) as Name,profile.firstname, profile. lastname, diary.post_id, date_format(diary.created_date,'%M %D %Y') AS cr_date, diary.user_id,diary.diary_title ,diary.description,diary.created_date,diary.multimedia, diary.location,profile.picture AS picture from diary inner join profile on diary.user_id = profile.user_id where (diary.Diary_title like '%$keyword%' or description like '%$keyword%' or diary.location like '%keyword%') and diary.Status_d=1 order by diary.created_date desc" ; 
            
            $result1 = $con->query($sql1);
            if ($result1->num_rows > 0) {
            // output data of each row
            while($row = $result1->fetch_assoc()) {
                $table_names[]=$row;
            }
        } else {
            echo "<h2> No Results found for this post. Try again </h2>";
        }
        ?>

<style>

.name{
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
div.friend_sugg
{
  background-color: #ffeecc;
  position:fixed;
  top: 100px;
  width:200px;
  right: 5px;
  font-weight:bold;
}
.comments{
}

div.up_birth
{
  background-color: #ffeecc;
  position: fixed;
  top: 400px;
  width:200px;
  right: 5px;
  font-weight:bold;

}
.green{
    color:green;
}
.red{
    color:red;
}
.img{
    
}
.multimedia{
 padding-left: 100px;   
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script type="text/javascript">


$(document).on("click", ".add-comment-button", function(){
            //alert('sasa');
            var url = "home_functions.php";
            var post_id = $(this).attr('post_val');
            //alert(post_id+"---"+$('#add_comment_'+post_id).val());
            var desc = $('#add_comment_'+post_id).val();
            if(desc.trim()!== ''){
                //alert(desc);
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
            }
            
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
function get_file_type(abc){
    return abc.split('.').pop();
}

    $(document).ready(function(){
        //var comments = get_comments();
        //home_page_diaries();
        //console.log(comments);
        //print_comments(comments);
        //map_comments(comments);
        var current_user = <?php echo $_SESSION['user_id'] ?>;
        //alert(current_user);
        var comments = <?php echo json_encode($comments); ?>;
        for(var key in comments){
           // console.log(comments[key]);
        }
        var diaries = <?php echo json_encode($table_names);?>;
        for(var key in diaries){
            console.log(diaries[key]['picture']);
        }


        for(var key in diaries){
            //console.log(diaries[key]);
            has_user_liked(diaries[key]['post_id']);
            var multimedia_file_display="";
            if(diaries[key]['multimedia']!=="" && diaries[key]['multimedia']!= null){
                //alert(get_file_type(diaries[key]['multimedia']));
                if(get_file_type(diaries[key]['multimedia']) === 'mp4'){
                    multimedia_file_display = "<div class='multimedia'>'<video controls autoplay='autoplay'><source src='uploads/"+diaries[key]['multimedia']+"' type='video/mp4' style='padding-left:100px;'></video></div>";
                }
                else if(get_file_type(diaries[key]['multimedia']) === 'jpg' || get_file_type(diaries[key]['multimedia']) === 'jpeg' || get_file_type(diaries[key]['multimedia']) === 'png') {
                    multimedia_file_display = "<div class='multimedia'><img class='img' src='uploads/"+diaries[key]['multimedia']+"'width=300 height=300/></div>";
                }
                else{}

            }
            var html="";
            html += "<div class='main-container' id='post_"+diaries[key]['post_id']+"'><div class='name_title'><div class='name'><a href='friendsprofile.php?id="+diaries[key]['user_id']+"'><h6><strong>"+diaries[key]['Name']+"</strong></h6></a><img src='uploads/"+diaries[key]['picture']+"' width=75 height=75/></div><div class='title'><span><h3>"+diaries[key]['diary_title']+"</h3><h6 class='date'>"+diaries[key]['cr_date']+"</h6><h5>"+(diaries[key]['location']!==""?"<span class='glyphicon glyphicon-map-marker'></span><a href='http://localhost/project/location.php?loc="+diaries[key]['location']+"'<span class='location'>"+diaries[key]['location']+"</span></a>":"")+"</h5><strong></strong></div></div><div class='desc_comment'><div class='description'><h5>"+diaries[key]['description']+"</h5></span>"+multimedia_file_display+"</div><div class='likes_block'>Likes:<span id='like_block_"+diaries[key]['post_id']+"'></span><span class='like_symbol' id='like_symbol_"+diaries[key]['post_id']+"'></span></div><div id='comments_"+diaries[key]['post_id']+"' class='commments'><h6>----comments here----</h6></div></div></div>";
            $("#page-content").append(html);
            get_likes_by_post(diaries[key]['post_id']);
        }
        
        for(var key in diaries){
            var commentHtml = "";
            commentHtml += "<div class='comment-container' id='comment-container-"+diaries[key]['post_id']+"'>";
            for(var key_1 in comments){
                if(diaries[key]['post_id'] == comments[key_1]['post_id']){
                    

                    //console.log(comments[key_1]);
                    commentHtml += "<div class='comment-container-inside'><span class='name'><a href='friendsprofile.php?id="+comments[key_1]['commentor']+"'>"+comments[key_1]['firstname']+" "+comments[key_1]['lastname']+"  </span></a><span>" + comments[key_1]['Comment']+"</span>"+(current_user == comments[key_1]['commentor'] ? "<span comment_id='"+comments[key_1]['comment_id']+"' class='glyphicon glyphicon-remove'></span>":"" )+"</div>";
                    commentHtml += "";

                    /*commentHtml += "<div><input id='add_comment_"+comments[key_1]['post_id']+"' post_val='"+comments[key_1]['post_id']+"' type='text' style='width:500px'><input class='add-comment-button' type='button' value='Add Comment' post_val='"+comments[key_1]['post_id']+"'></div>";*/
                }

            }
            commentHtml += "</div><div><input id='add_comment_"+diaries[key]['post_id']+"' post_val='"+diaries[key]['post_id']+"' type='text' style='width:500px'><input required class='add-comment-button' type='button' value='Add Comment' post_val='"+diaries[key]['post_id']+"'></div>";
                $("#comments_"+diaries[key]['post_id']).html(commentHtml);
        }

        


    }); 
</script>
<body>
    
    <!-- <button id='search-button'>search</button> -->
    <div>
        <div id='page-content' style="width:70%; float:left; overflow-y:auto; height:600px">
        </div>
    </div>
</body>
<?php
    /*$res=mysqli_query($con,$query);
	if(mysqli_num_rows($res)>0)
	{
		echo "<h3> Results for Posts: </h3> "."<br>";
        echo "<table>
        <tr>
        <th> Title </th>
        <th> Description </th>
        <th> Date </th>
        </tr>";
        while($row = mysqli_fetch_assoc($res)) 
        {
            echo "<tr>";

            echo "<td>".$row["Diary_title"]."</td>";
            echo "<td>".$row["description"]."</td>";
            echo "<td>".$row["created_date"]."</td>";
            echo "</tr>";
        }
	}
	else{
		echo "<h2> No Results found for this post . Try Again </h2>";
	}*/

}
//-------------------------------------------------------
else 
	if($_POST['key']=='people')
{
	
    $query="select * from profile where username like '%$keyword%' or firstname like '%$keyword%' or lastname like '%$keyword%'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) > 0) {
    
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {

            //echo $row["firstname"]. " ". $row["lastname"]."<br>";
            echo "<div class= f_list>";
            echo "<a href='friendsprofile.php?id=".$row["user_id"]."' >"."<h3>".$row["firstname"]. " ". $row["lastname"]."</h3>"."</a>";
            $picture=$row["picture"];
            echo "<img src=uploads/$picture width=150 height=135 />"."<br>";
            echo "</div>";
        }
    } 
    else {
        echo "<h2> No Results found for this user . Try again </h2>";
    }
}
else 
    if($_POST['key']=='interests')
{
    
    $query="select * from profile where fsport like '%$keyword%' or fplayer like '%$keyword%' or fteam like '%$keyword%'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) > 0) {
        echo "<h2> People with similar interests </h2>  "."<br>";
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {

            //echo $row["firstname"]. " ". $row["lastname"]."<br>";
            echo "<a href='friendsprofile.php?id=".$row["user_id"]."' >".$row["firstname"]. " ". $row["lastname"]."</a><br>";

        }
    } 
    else {
        echo "<h2> No People wth similar interests. Try again </h2>";
    }
}
	
 
?>