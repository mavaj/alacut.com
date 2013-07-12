<?php 
session_start();
require_once("../lib/db.function.php");
include('../config/config1.php');
if($_SESSION['login_user_id']=="")
{
header("Location: index.php");
}
$member_id = $_SESSION['login_user_id'];

$myProfileSql = "SELECT `full_name`,`job`,`education`,`address`,`relationship`,`married_to`,`url`,`handle`,
	`profile_photo_name` FROM `alacut_member` WHERE  member_id = '".$_REQUEST['id']."' AND active = '1'";
	$myProfileRes = mysql_query($myProfileSql);
	$myProfileInfo = mysql_fetch_array($myProfileRes);

	$videoPostQry = "SELECT u.video_id,u.video_upload_by,u.video_object,u.video_name,u.visit,u.video_type,u.like,u.dislike,a.full_name,u.uploaded_date, 
     u.video_name,u.video_desc FROM `upload_video` u INNER JOIN `alacut_member` a ON a.member_id = u.video_upload_by 
     WHERE u.video_upload_by ='".$_REQUEST['id']."' AND u.status != '2' ORDER BY u.uploaded_date DESC";
	$videoPostRes = mysql_query($videoPostQry);
		
	$totalPostsQry = "SELECT COUNT(`video_id`) AS count FROM `upload_video` WHERE `video_upload_by`='".$member_id."' AND `status` != '2'";
	$totalPostsRes = mysql_query($totalPostsQry);
	$totalPostsInfo = mysql_fetch_array($totalPostsRes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>All Posts</title>
<link type="text/css" rel="stylesheet" href="../css/colorbox.css" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery-ui-1.8.15.custom.css" />
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../js/jquery.colorbox-min.js"></script>
<script src="../js/jquery-ui-1.8.14.custom.min.js"></script>
<script src="../js/jquery-ui-timepicker-addon.js"></script>

<script>
  $(function() {
    $("#calender").datepicker();
  });
</script>
<script>
var user_id = '<?php echo $_REQUEST['id']?>';
$(document).ready(function () { 
	
	$("#btnSearch").click(function(){
		$(".button").colorbox({scrolling: false, overlayClose: false, escKey: false, opacity: 0.8});
		$("#btnSearch").attr("href","Search.php?search="+$("#txtFriendSearch").val());
		});
		
	$(".left_body_box").hide();
	$(".left_vid_cont").hide();
	
	$(".video").click(function(){
		$(".left_body_box").show();
		$(".left_vid_cont").show();
		
	});
	
	$("#Search").click(function()
	{
		//alert(user_id);
		var name = $("#SearchBox").val();
		var date = $("#calender").val();
		if(date == "date"){
			date = "";
		}
		if(name == "name"){
			name = "";
		}
		if(name != "" || date != "")
		{
			$.ajax({
			type: "POST",
			url: 'ajax/searchVideoInAjax.php',
			data: 'searchName='+name+'&date='+date+'&uId='+user_id,
			success: function(data) {
				alert('');
				$(".left_body").html(data);
			},
			error: function(e) {
				alert("There is somme error in the network. Please try later.");
			}
			});
		}
 });
});

function lageVideoId(video_id,user_id)
{  
	$.ajax({
			type: "POST",
			url: 'ajax/postLargeVideoInAjax.php',
			data: 'lvId=' + video_id+'&userId='+user_id,
			success: function (data) {
				$("#largeVideo").html(data);
			},
			error: function (e) {
				alert("There is somme error in the network. Please try later.");
			}
		});
}
</script>
</head>
<body>
<form name="profile" method="post" action="">
<div class="wrapper">
  <?php include("header.php");?>
<div class="inner">
<div class="inner_left">
	<div class="left_head1">
        <div class="head">
          <h1>All Posts</h1>
        </div>
      </div>  
      <!--<div id="txt"><input type="text" id="SearchBox" name="SearchBox" value="name" onfocus="if(value=='name'){value=''; }" onblur="if(value==''){value='name'; }" onclick="if(value=='name'){value='';}"/></div>
     <div><input type="text" id="calender" name="calender" value="date" onfocus="if(value=='date'){value=''; }" onblur="if(value==''){value='date'; }" onclick="if(value=='date'){value='';}"/></div>
      <div id="btn"><button type="button" id="Search">Search</button></div>-->
      
      <!--<div class="search">
          <div>
            <div>
              <input type="text" name="textfield3" id="textfield3">
            </div>
            <div>
              <input type="text" name="textfield4" id="textfield4">
            </div>
            <div>
              <input type="submit" name="button2" id="button2" value="Search">
          </div>
        </div>
      </div>-->
      <header class="search">
      	<div>
        <article class="">Nmae: 
      	  <input name="SearchBox" type="text" class="input1" id="SearchBox">
      	</article>
      	<article>Date: 
      	  <input type="text" name="calender" id="calender" class="input2">
   	    </article>
      	<nav>
      	  <input type="submit" name="button2" id="button2" value="Search">
      </nav>
        </div>
      	<nav></nav>
      </header>
      
      <div class="left_body_box" id="largeVideo">
      <div id="largeVideo">
      <iframe id="div_youTube" width="640" height="360" src="" frameborder="0" allowfullscreen></iframe>
      <div class="left_vid_cont">
          <div class="vid_name"><?php echo $videoPostInfo['video_name']?></div>
          <div class="user_name"><?php echo $videoPostInfo['full_name']?></div>
          <div class="sn_img"><a href="#"><img src="../images/in.jpg" width="30" height="30" alt="in" /></a><a href="#"><img src="../images/fb.jpg" width="30" height="30" /></a><a href="#"><img src="../images/twt.jpg" width="30" height="30" /></a><a href="#"><img src="../images/alacut.jpg" width="30" height="30" /></a></div>
        </div>
        </div>
      </div> 
     
    <div class="left_body">
	<?php while($videoPostInfo = mysql_fetch_array($videoPostRes)) {
		$thumbImgPath = '';
		if($videoPostInfo['video_type']=="youtube"){
			$thumbImgPath = "http://img.youtube.com/vi/".$videoPostInfo['video_object']."/1.jpg";
		}else{
			$vimeoInfo = vimeoVideoDetails($videoPostInfo['video_object']);
			$thumbImgPath = $vimeoInfo->thumbnail_medium;
		}
	 ?>
        <div class="left_thumb">
          <div class="posts" onclick="lageVideoId('<?php echo $videoPostInfo['video_id'];?>','<?php echo $_REQUEST['id'];?>')"><a href="#"><img class="video" src="<?php echo $thumbImgPath;?>" alt="" width="140" height="74" data="<?php echo $videoPostInfo['video_object']?>" videoType = "<?php echo $videoPostInfo['video_type'] ?>"/></a>
            <div class="home_des_thumb">
              <div class="post_title"><?php echo $videoPostInfo['video_name']?></div>
              <div class="name"><?php echo $videoPostInfo['full_name']?></div>
            </div>
            <?php
			if($videoPostInfo['like'] != 0 OR $videoPostInfo['dislike'] !=0)
			{
				$up_value = $videoPostInfo['like'];
				$down_value = $videoPostInfo['dislike'];
				$total = $up_value + $down_value;
				$up_per = ($up_value*100)/$total; 
				$down_per = ($down_value*100)/$total;?> 
				<div class="like_bar2">
				  <div class="dislike" style="width:<?php echo $down_per; ?>%;"></div>
				  <div class="like" style="width: <?php echo $up_per; ?>%;"></div>
				</div>
			<?php }?>
          </div>
        </div><?php } ?> 
      </div>
     
  </div>
  <div class="inner_right">
    <div class="user_img">
      <div class="pic">
        <?php if($myProfileInfo['profile_photo_name'] != ''){
                echo '<img src="../upload/profilePhotos/'.$myProfileInfo['profile_photo_name'].'" alt="" width="300" height="300"/>';
                }else{
                    echo '<img src="../images/no-image.jpg" alt="" width="300" height="300"/>';
            }?>
      </div>
      <div class="detail_box">
        <h1><?php echo $myProfileInfo['full_name']?></h1>
        <div class="detail"><strong><img src="../images/work.jpg" alt="" width="15" height="11" />Works at:</strong><?php echo "   "; echo $myProfileInfo['job']?></div>
        <div class="detail"><strong><img src="../images/sudies.jpg" alt="" width="15" height="11" />Studied:</strong><?php echo "   "; echo $myProfileInfo['education']?></div>
        <div class="detail"><strong><img src="../images/livesin.jpg" alt="" width="15" height="11" />Lives in:</strong><?php echo "   "; echo $myProfileInfo['address']?></div>
        <div class="detail"><strong><img src="../images/married.jpg" alt="" width="15" height="11" />Married to:</strong><?php echo "   "; echo $myProfileInfo['married_to']?></div>
      </div>
    </div>
    <div class="user_btn">
    <div class="button"><a href="#">Find&nbsp;Friends</a></div>
    <div class="button"><a href="myFriends.php">Friends</a></div>
    <div class="button"><a href="allPosts.php?id=<?php echo $_REQUEST['id'];?>"><?php echo $totalPostsInfo['count']?>&nbsp;&nbsp;Posts</a></div>
    <div class="button"><a href="editUserInfo.php" style="margin: 0;">Edit&nbsp;Info</a></div>
  </div>
  </div>
  
<div class="footer_inner"><a href="#">alacut</a> | <a href="#">Terms of Use</a> | <a href="#">Privacy Policty</a> | <a href="#">advertising</a> | <a href="#">2012 Alacut</a></div>
  </div>
</div>
</form>
</body>
</html>

            