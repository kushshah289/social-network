<?php include "header.php"; 

$con=mysqli_connect('localhost','root','','project_dbs');
if (!$con){
    die(" Connection Failed: ". mysqli_connect_error());
}

$comm = "select * from comments inner join profile on profile.user_id=comments.commentor WHERE comments.Status_c=1 ORDER by comments.comment_id";
       $result2= $con->query($comm);
       if ($result2->num_rows > 0) {
            // output data of each row
            while($row = $result2->fetch_assoc()) {
                $comments[]=$row;
            }
        }
        $sql1 = "select concat(profile.firstname,' ', profile.lastname) as Name, diary.post_id, date_format(diary.created_date,'%M %D %Y') AS cr_date, diary.user_id,diary.diary_title,diary.description,diary.multimedia,diary.created_date, profile.picture AS picture from diary inner join profile on profile.user_id= diary.user_id where diary.user_id = $_SESSION[user_id]  order by diary.created_date desc"; 
            
            $result1 = $con->query($sql1);
            if ($result1->num_rows > 0) {
            // output data of each row
            while($row = $result1->fetch_assoc()) {
                $table_names[]=$row;
            }
        } else {
            echo "0 results";
        }
        
        
//var_dump($table_names);
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

div.up_birth
{
  background-color: #ffeecc;
  position: fixed;
  top: 400px;
  width:200px;
  right: 5px;
  font-weight:bold;

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
            //console.log(comments[key]);
        }
        var diaries = <?php echo json_encode($table_names);?>;
        for(var key in diaries){
            //console.log(diaries[key]['multimedia']);
            alert(diaries[key]['multimedia']);
        }


        for(var key in diaries){
            //console.log(diaries[key]);
            var html="";
            html += "<div class='main-container' id='post_"+diaries[key]['post_id']+"'><div class='name_title'><div class='name'><a href='friendsprofile.php?id="+diaries[key]['user_id']+"'><h6><strong>"+diaries[key]['Name']+"</strong></h6></a><img src='uploads/"+diaries[key]['picture']+"' width=75 height=75/></div><div class='title'><span><h3>"+diaries[key]['diary_title']+""+(diaries[key]['multimedia']!==''?"<img src='uploads/"+diaries[key]['multimedia']+"'>":"")+"</h3><h6 class='date'>"+diaries[key]['cr_date']+"</h6></div></div><div class='desc_comment'><div class='description'><h5>"+diaries[key]['description']+"</h5    ></span></div><div id='comments_"+diaries[key]['post_id']+"' class='commments'><h6>----comments here----</h6></div></div></div>";
            $("#page-content").append(html);
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
            commentHtml += "</div><div><input id='add_comment_"+diaries[key]['post_id']+"' post_val='"+diaries[key]['post_id']+"' type='text' style='width:500px'><input class='add-comment-button' type='button' value='Add Comment' post_val='"+diaries[key]['post_id']+"'></div>";
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

</body>
</html>

