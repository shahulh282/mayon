<?PHP 

require  "src/dbms.php";
require  "src/User.php";
require  "src/post-module.php";
require  "src/comments-module.php";
require  "src/review-module.php";
require  "pager/Paginated.php";
require  "pager/DoubleBarLayout.php";

//Page Routes
session_start();

/*if(!isset($_SESSION["currentuser"]))
	header("location:login.php");*/

/* Ajax Request Respond Block --Start */

//New Comments Adding
if(isset($_POST["addComment"]))
if(isset($_POST["uid"],$_POST["pid"],$_POST["comment"]))
{
$_SERVER["db"]->query(sprintf("INSERT INTO 
							comments(uid,pid,comment,level,date) 
							VALUES(%s,%s,\"%s\",0,CURRENT_TIMESTAMP)"
							,$_POST["uid"],$_POST["pid"],$_POST["comment"]));
header("location:single-post.php?id={$_POST["pid"]}");
}
/* Ajax Request Respond Block --End   */

/* Get */
if(isset($_GET['q']))
{
$key=explode(" ",$_GET['q']);

	
$sql=sprintf("SELECT DISTINCT pid from taglist where tag REGEX(\"/%s/i\") or tag  REGEX(\"/%s/i\") UNION 
			  SELECT DISTINCT pid from catagory where category REGEX(\"/%s/i\") or category REGEX(\"/%s/i\")",$key[0],$key[1],$key[0],$key[1]);
echo $sql;
$_SERVER["db"]->query($sql);
print_r($_SERVER["db"]->recordsArray(false));
exit();
}

if(!isset($_GET["id"]))
{
$_SERVER["db"]->query("SELECT pid from posts order by ptimestamp limit 1");
$_GET["id"]	=$_SERVER["db"]->data();
}

if(isset($_POST["uid"],$_POST["pid"],$_POST["cid"],$_POST["reply"]))
{
if(!empty($_POST["uid"]) && !empty($_POST["pid"]) && !empty($_POST["reply"]) && !empty($_POST["cid"]))
{
$_SERVER["db"]->query("INSERT into comments(uid,pid,cid,reply,level,date,lastupdated) values(%s,%s,%s,\"%s\",1,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)",$_POST["uid"],$_POST["pid"],$_POST["cid"],$_POST["reply"]);

echo "Reply Added";
}
else
echo "Some Error";
}















if(isset($_GET['tag']))
{
header("location:communitypage.php?search=tag".$_GET["tag"]);
}




//Utility Functions
function fetch_tags(){
	$tags=array();
		$_SERVER['db']->query("Select ptags from posts");
		$rec=$_SERVER['db']->recordsArray(false);
		
		forEach($rec as $row)
		{
			forEach($row as $data)
			{
				$temp=preg_split('/,/',$data);
				forEach($temp as $r)
				array_push($tags,$r);
			}
		}
		$tags=array_unique($tags,SORT_STRING);
		$taglist="";
		
		forEach($tags as $unique){
			$taglist.= sprintf("<li><a href='communitypage.php?tag=%s'>%s</a></li>",$unique,$unique);
		}
	
	return $taglist;
}
function search_by_tags($tag){
	$_SERVER["db"]->query("SELECT * from posts where ptags regexp(\"%s\")",$tag);
	$_SERVER["db"]->recordsArray();
}
function find_post_by_title($title)
{
	$sql=sprintf("SELECT * from posts where ptitle=REPLACE(\"%s\",\"-\",\" \")",$title);
 $_SERVER['db']->query($sql);
 $rec=$_SERVER['db']->recordsArray(false);
    if(!is_array($rec))
	{
		die("No Such Posts");
    }
	
	return $rec;	
}


















function access_time($datetime){
	return date("M d,y",strtotime($datetime));
	return $final['month']." ".$final['date'].",".$final['year'];
}



function image($img=NULL){
	if($img==null)
		$content="images/note.png";
		else
		$content=$img;
	return $content;
}

function paragraph($content=NULL)
{	
	if($content==null)
	$content="No Content Available";
	$content=preg_split("/\./",$content);
	$sentence="";
	forEach($content as $row){
		$sentence.="<p>".$row.".</p>";

	}
	return $sentence;
}

function tags($tags="Uncatagorized")
{
	$content="";
	if($tags==NULL)
		$tags="Uncatagorized";
	$tags=preg_split("/,/",$tags);	
	forEach($tags as $tag)
	{
		$content.="<a href='' class='post-tag'>".$tag."</a> ";
	}	
 return $content;
}

function create_single_post($title)
{
 //Getting Post contents
 $sql=sprintf("SELECT * from posts where ptitle=REPLACE(\"%s\",\"-\",\" \")",$title);
 $_SERVER['db']->query($sql);
 $record=null;
 $record=$_SERVER['db']->recordsArray(false);
 
 if($record!=null && is_array($record)){
	
	//If Post found in database
	$sql=sprintf("SELECT * from users where uid=\"%s\"",$record['pauthorid']);
	$_SERVER['db']->query($sql);
	$user=User::createFromRecord($_SERVER['db']->recordsArray(false));
	
	return sprintf("<div class='blog-post single-post'>
					    <div class='post-content'>
						    <h1>Question</h1>
							<div class='post-meta date'>
							    <i class='material-icons'>access_time</i> %s
							</div>
							<div class='post-meta user'>
								<i class='material-icons'>perm_identity</i> Posted by <a href='%s'>%s</a>
							</div>
							<div class='post-meta category'>
							  	<i class='material-icons'>folder_open</i>%s
							</div>
						</div>
							<a href='single-post.php'><img src='%s' alt=''></a>
							<div class='post-content'>
								%s
							</div>
					</div>",
								access_time($record['ptimestamp']),
								$record['ptitle'],
								$user->getName(),
								tags($record['ptags']),
								image($record['pimg']),
								paragraph($record['pcontent'])
				  );
 }
 else 
	return "<div class='blog-post single-post' style='padding:30px;margin:auto;'>
			    <h1>
				No post Found With Given Title
				Your can grab posts by following
				</h1>
				<a href='single-post.php?fetch=tags'>Search By tags</a>
				<a href='single-post.php?fetch=catagories'>Search By Categories</a> 
			</div>";

}

?>





	
<!doctype html>
<html lang="en" class="no-js">
<head>
	<title>Question</title>

	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,400i,500,500i,600,700&display=swap" rel="stylesheet">
	
	<link rel="stylesheet" href="css/studiare-assets.min.css">
	<link rel="stylesheet" type="text/css" href="css/fonts/font-awesome/font-awesome.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/fonts/elegant-icons/style.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/fonts/iconfont/material-icons.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" href="images/logo.png">
    <!--  apple-touch-icon -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" href="images/logo.png">

</head>
<body>

	<!-- Container -->
	<div id="container">
<?PHP include "src/header.php"?>
			
		<!-- page-banner-section 
			================================================== -->
		<section class="page-banner-section">
			<div class="container">
				<h1>Questions</h1>
				<ul class="page-depth">
					<li><a href="index.php">Home</a></li>
					<li><a href="communitypage.php">Community</a></li>
					<li><a href="single-post.php">Question</a></li>
				</ul>
			</div>
		</section>
		<!-- End page-banner-section -->

		<!-- blog-section ================================================== -->
		
		<section class="blog-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-7">

						<div class="blog-box">

							<?PHP 
							
								if(isset($_GET['title']))
							          echo create_single_post($_GET['title']);
								else if(isset($_GET['id']))
									echo post_template($_GET['id'],3);		
									
							?>
								<div class="comments-holder">
								<h2><?PHP echo comments_count($_GET['id']);?> Comments</h2>
								<p>Join the discussion and tell us your opinion.</p>

								<ul class="comment-list">
								<?PHP echo comments_section();?>
								</ul>
							</div>

							<form id='comment-form' method='POST' class="comment-form" action='single-post.php'>
								<h2>Leave a Reply</h2>
								<p>Loggedin as 
								<?PHP 
								echo $_SESSION['currentuser']->getName();
								?> <a href="login.php?logout">Log out?</a></p>
								<label>Comment</label>

								<?PHP 
								if(isset($_SESSION['currentuser']))
								echo sprintf("<input type='hidden' name='uid' value='%s'>",$_SESSION["currentuser"]->getUid());
								echo sprintf("<input type='hidden' name='pid' value='%s'>",$_GET["id"]);
								?>
								<input name='addComment' type='hidden'/>
								<div id='msg' style='display:none'></div>
								<textarea name='comment' id='comment'></textarea>
								<button type="submit">
									Post Comment
								</button>
							</form>
							
						</div>
					</div>

					<div class="col-lg-4 col-md-5">
						<div class="sidebar">
							<div class="search-widget widget">
								<form class="search-form" action='single-post.php' method="POST">
									<input type="search" name='search' class="search-field" placeholder="Enter keyword...">
									<button type="submit" class="search-submit">
										<i class="material-icons">search</i>
									</button>
								</form>
							</div>

							<div class="category-widget widget">
								<h2>Categories</h2>
								<ul class="category-list">
									<?PHP 
									function catagory_list()
									{
										$sql=sprintf("SELECT DISTINCT category from category where pid=%s",$_GET["id"]);
										$_SERVER["db"]->query($sql);
										$category=$_SERVER["db"]->recordsArray(false);
										foreach($category as $row)
											echo "<li><a href='communitypage.php?search=category:".$row['category']."'>".$row['category']."</a></li>";
									}
									catagory_list();
									?>
								</ul>
							</div>

							<div class="tags-widget widget">
								<h2>Tags</h2>
								<ul class="tags-list">
									<?PHP 
									
									function tag_list(){
										$sql=sprintf("SELECT DISTINCT tag from taglist where pid=%s",$_GET["id"]);
										$_SERVER["db"]->query($sql);
										
											$taglist=$_SERVER["db"]->recordsArray(false);
										foreach($taglist as $row)
											echo "<li><a href=\"communitypage.php?search=tag:".$row["tag"]."\">".$row["tag"]."</li>";
									}
									tag_list();
									?>
									
								</ul>
							</div>
						</div>
					</div>

				</div>
						
			</div>
		</section>
		<!-- End blog section -->

		<!-- footer 
			================================================== -->
			<?PHP include("src/footer.html");?>
		<!-- End footer -->

	</div>
	<!-- End Container -->

	
	<script src="js/studiare-plugins.min.js"></script>
	<script src="js/jquery.countTo.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en"></script>
	<script src="js/gmap3.min.js"></script>
	<script src="js/script.js"></script>
	<script>
	var $form=$(".comment-form").on('submit',
		(e)=>
	{
	e.preventDefault();
	$("input[name='pid']").val($("div[data-pid]").attr("data-pid"));
	if($("#comment").val()!="")
	$.ajax({
		type:"POST",
		url:"single-post.php",
		data:$form.serialize(),
		dataType:"json",
		success:(data)=>
		{
			if(data.info=="success")
				{
					$("#msg").text(data.msg);
					$("#msg").css("display","block");
					
				}
				
		}  })

else
	alert("Can't Post Empty Comment");
})
	
	
	</script>
</body>
</html>
<?php

function comments_count($id)
{
$sql=sprintf("SELECT count(*) from comments where pid=%s",$id);
$_SERVER	["db"]->query($sql);
return $_SERVER["db"]->data();
}

function comments_section()
{	
	if(isset($_GET['id']))
	{
	$sql=sprintf("SELECT id,comment,DATE(date) as date,uid
	 from comments where  pid=%s and level=0 limit 10",$_GET['id']);
	$_SERVER["db"]->query($sql);
	$comment=$_SERVER["db"]->recordsArray(false);
	
	echo "<ul class='comment-list'>";
	if(count($comment)<1)	
	echo "<li>Start Discussion By Posting A comment</li>";
	else
		forEach($comment as $row)
		{
		$sql=sprintf("select name,profile from users where uid=%s",$row['uid']);
		//echo $sql;
		$_SERVER["db"]->query($sql);
		$u=$_SERVER["db"]->SingleRow();
		if($u)
		{
			$u["profile"]=(empty($u["profile"]))?"upload/blog/avatar4.jpg":$u["profile"];
		echo "<li>
				<div class='image-holder'>
					<img src=\"".$u['profile']."\" alt=''>
				</div>
				<div class='comment-content'>
					<h2>".$u["name"]."<span>".date("M d, Y",strtotime($row["date"]))."</span>
						<a><i class='fa fa-commenting-o' uid='{$row['id']}'></i>Reply</a>
						<form action='single-post.php'  method='post'>
			<input name='uid' value='{$row['uid']}' type='hidden' />
			<input name='pid' value='{$_GET['id']}' type='hidden' />
			<input name='cid' value='{$row["id"]}' type='hidden' />
			<input name='reply' type='text'/>
			<button type='submit' name='reply' value=''>Reply</button>
			</form>
					</h2>
						<p>".$row['comment']."</p>
					";
					echo reply($row['id']);
					echo "</div></li>";

		}
		
				
		
		}
	echo "</ul>";
	}

}

function reply($cid){
$sql=sprintf("SELECT comment,uid,date from comments where cid=%s order by last_update",$cid);	
$_SERVER["db"]->query($sql);
$replies=$_SERVER["db"]->recordsArray(false);
echo "<ul class='depth-comment'>";
forEach($replies as $reply)
	{
	$sql=sprintf("SELECT name,profile from users where uid=%s",$reply['uid']);
	$_SERVER["db"]->query($sql);
	$u=$_SERVER["db"]->singleRow();
	$u["profile"]=(empty($u["profile"]))?"upload/blog/avatar4.jpg":$u["profile"];
	echo "<li>
			
		  <div class='image-holder'>
		  <img src=\"".$u["profile"]."\" alt=''>
		  </div>
		  <div class='comment-content'>
			<h2>
			 ".$u["name"]."
			  <span>".date("M d, Y",strtotime($reply['date']))."</span>
			</h2>
			<p>".$reply["comment"]."</p>
			</div>
		 	</li>
			</ul>";
	}

}

?>