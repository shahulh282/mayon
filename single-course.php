<?PHP 
	require "src/dbms.php";
	require "src/User.php";
	require "src/course-module.php";
	require "src/review-module.php";
	session_start();
		

	$id=$_GET['id'];
	$sql=sprintf("SELECT *,
	(SELECT AVG(rating) from review where cid=courseid) as rating 
	from course where courseid =%s",$id);
	try 
	{
    $_SERVER["db"]->query(sprintf("SELECT count(rating) from review where cid=%s",$id));
	$votes=$_SERVER["db"]->data();
	}
	catch(Exception $e)
	{
	echo $e->message();
	}

	$_SERVER['db']->query($sql);
	$sc=$_SERVER['db']->singleRow();
	if(isset($_GET["addreview"]))
	add_review($_POST["uid"],$_POST["cid"],$_POST["review"],$_POST["rating"]);
	function add_review($uid,$cid,$review,$rating){

	$r=$_SERVER["db"]->query(sprintf("INSERT INTO review values(NULL,%s,%s,\"%s\",%s)"),$cid,$uid,$review,$rating);
	echo mysqli_error_list($_SERVER->gconn());

}
if(isset($_POST["submit"]))
if(isset($_POST["rating"],$_POST["review"],$_POST["userid"],$_POST["courseid"]))
	review_handler($_POST["rating"],$_POST["review"],$_POST["userid"],$_POST["courseid"]);

?>

<!doctype html>

<html lang="en" class="no-js">
<head>
	<title>Dhruv Math Physics</title>

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
		<!-- Header================================================== -->
			<?PHP include "src/header.php" ?>
		<!-- End Header -->
		
		<!-- page-banner-section class="widget profile-widget"
			================================================== -->
		<section class="page-banner-section">
			<div class="container">
				<h1>Course Page</h1>
				<ul class="page-depth">
					<li><a href="index.php">Home</a></li>
					<li><a href="courses.php">Courses</a></li>
					<li><?PHP echo "<a href=\"single-course.php?id=\"".$sc['courseid']."\" >".$sc['class']." ".$sc['subject']."</a>";?></li>
				</ul>
			</div>
		</section>
		<!-- End page-banner-section -->

		<!-- single-course-section 
			================================================== -->
		<section class="single-course-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">

						<div class="single-course-box">

							<!-- single top part -->
							<div class="product-single-top-part">
								<div class="product-info-before-gallery">
									<div class="course-author before-gallery-unit">
										<div class="icon">
											<i class="material-icons">account_box</i>
										</div>
										<div class="info">
											<span class="label">Teacher</span>
											<div class="value">
												<a href="single-teacher.php">Michael Arnett</a>
											</div>
										</div>
									</div>
									<div class="course-category before-gallery-unit">
										<div class="icon">
											<i class="material-icons">bookmark_border</i>
										</div>
										<div class="info">
											<span class="label">Category</span>
											<div class="value">
												<a href="#">XII <span>/</span></a>
												<a href="#">MATHS</a>
											</div>
										</div>
									</div>
									<div class="course-rating before-gallery-unit">
										<div class="star-rating has-ratings">
											<span class="rating"><?PHP echo round($sc['rating'],2);?></span>
											<span class="votes-number">
												<?PHP 
											
											
											
											echo $votes!=0?$votes:0;
											
											?> Votes</span>
										</div>
									</div>
								</div>
								<div class="course-single-gallery">
									<img src=<?PHP echo "\"".$sc['thumb']."\"";?> alt="">
								</div>

							</div>

							<!-- single course content -->
							<div class="single-course-content">
								<h2>What Will I Learn?</h2>
								<p><?PHP echo $sc['will_learn'];?></p>
								<h2>Learning Objectives</h2>
								<div class="row">
								<?PHP echo learning_objectives($sc['learn_obj']); ?>
								</div>
								<h2>Prior Knowledge</h2>
								
								<p><?PHP echo $sc['prior_know'];?></p>
								</div>
							</div>
							<!-- end single course content -->

							<!-- course reviews -->
							<div class="course-reviews">
								<div class="course-review-title">
									<h3>
										<i class="material-icons">chat_bubble_outline</i>
										Student Reviews
									</h3>
								</div>
								<div class="course-reviews-inner">
									<div class="ratings-box">
										<div class="rating-average">
											<p>Average rating</p>
											<div class="average-box">
												<span class="num"><?PHP echo round($sc["rating"],2);?></span>
												<span class="ratings">
													<?PHP 
													function rating_star_printer($rating)
													{	$r=$rating-(int)$rating;

														for($i=0;$i<(int)$rating;$i++)
														echo "<i class='fa fa-star'></i>";
														if($r>0.5)
														echo "<i class='fa fa-star-half-o'></i>";
														
													}
													
													rating_star_printer(is_int($sc["rating"])?$sc["rating"]:0);
													?>
													
												</span>
												<span class="txt"><?PHP echo $votes;?> Ratings</span>
											</div>
										</div>
									
										<div class="detailed-rating">
											<p>Detailed Rating</p>
											<div class="detailed-box">
											<?PHP 
//Detailed Rating
	$_SERVER["db"]->query(sprintf("SELECT count(rating) from review where cid=%s",$sc["courseid"]));
	$total_rating=$_SERVER["db"]->data();
	$r=$_SERVER["db"]->query(sprintf("
	SELECT 
	IF( rating=1,count(rating),0) as star_1, 
    IF( rating=2,count(rating),0) as star_2,
	IF( rating=3,count(rating),0) as star_3,
	IF( rating=4,count(rating),0) as star_4,
	IF( rating=5,count(rating),0) as star_5
	FROM `review` where cid=%s",$_GET["id"]));
	$star=mysqli_fetch_row($r);
	//var_dump($star);
	if(!is_array($star))
	$star=array(0,0,0,0,0);
	echo "<ul class=detailed-lines>";
	$total=array_sum($star);
	for($i=4;$i>=0;$i--)
	{ $s=$i+1;
	  $progress=$total !=0 && $star[$i]!=0 ?$star[$i]/$total*100:0;
		echo "<li>
				<span>{$s} Stars</span>
				<div class='outer'>
					<span class='inner-fill' style='width:{$progress}%'></span>
				</div>
				<span>{$star[$i]}</span>
			 </li>";
	}													
	echo "</ul>";
?>
</div>

<ul class="comments">
	<?PHP 
		$sql=sprintf("SELECT * from review  where cid =%s order by rating limit 5",$_GET["id"]);
		$_SERVER["db"]->query($sql);
		$r=$_SERVER["db"]->recordsArray(false);
		
		forEach($r as $review)
		{
		$user=User::getUserById($review["uid"]);
		if($user==null)
		continue;
		$img=$user->getProfile();
		if($img=="")
			$img="upload/portfolio/portfolio-image-1.jpg";
		
		echo "<li>
		<div class='image-holder'>
		<img src=\"{$img}\" alt=''>
		</div>
		<div class='comment-content'>
		<h2>";
		echo $user->getName();
		echo "<p>{$review["review"]}</p>";
		echo "<span class='rating'>";
		for($i=0;$i<$review['rating'];$i++)
		echo "<i class='fa fa-star'></i>";		
		echo "</span></h2>
		
		</div></li>";
			;
		}
		function review_template()
		{

		}
		/*forEach($reviewset as $review)
		{
			
    	}
		else
		echo "<li>No Reviews</li>";									
	}*/
	?>
					

											</div>
										</div>

									<form class="add-review"  action="single-course.php" method='post' novalididate>
									<div id="msg" style='display:none'></div>
										<h1>Add a Review</h1>
										<label>Your rating</label>
										<input type='hidden' name='uid' <?PHP $uid=isset($_SESSION["currentuser"])?$_SESSION["currentuser"]->getUid():"";
										echo "value={$uid}";?>  />
										<input type='hidden' name='cid' <?PHP $cid=isset($_GET["id"])?$_GET["id"]:"";
										echo "value=\"{$cid}\"";  ?>/>
										<select name='rating'>
											<option value=''>Rate...</option>
											<option value='5'>Perfect</option>
											<option value='4'>Good</option>
											<option value='3'>Average</option>
											<option value='2'>Not that bad</option>
											<option value='1'>Very Poor</option>
										</select>
										<label>Your Review</label>
										
										<textarea name='review'></textarea>
										
										<button id='submit-review' type="submit" name='submit' >Submit</button>
									</form>
							<!-- end course reviews -->
									</div>
									</div>		
									</div>
<div class="col-lg-4">
		<div class="sidebar">
			<div class="widget course-widget">
					<p class="price">
						<span class="price-label">Last Updated</span>
						<?PHP 
										//Last Updated
										$_SERVER["db"]->query(sprintf("SELECT 
										IF (TO_DAYS(NOW())-TO_DAYS(max(lastedited))!=NULL,TO_DAYS(NOW())-TO_DAYS(max(lastedited)),0)
										as lastupdated from downloads where courseid=%s",$_GET["id"]));
										$lastupdated=$_SERVER["db"]->data();
										if($lastupdated=0)
										echo "<b>Today</b>";
										elseif($lastupdated=1)
										echo "<b>Yesterday</b>";
										elseif($lastupdated>2)
										echo "<b>{$lastupdated} days ago</b>";
										elseif($lastupdated>7)
										echo "<b>{($lastupdated/7)} week ago</b>";
									?>
								
					</p>
<?PHP 
$sql=sprintf("SELECT title,
CONCAT(\"%s/upload/collection/downloads/\",filename) as href 
from downloads where courseid=%s",$_SERVER["HTTP_HOST"],$_GET["id"]);

$_SERVER["db"]->query($sql);
$dwnlds=$_SERVER["db"]->recordsArray(false);
if(count($dwnlds)>0)
echo "<a class='button-one' href='' download>Download All</a>";
else
echo "<a class='button-one' href=''>No Downloads Available</a>";
?>
<div class="product-meta-info-list">
<h3>Download</h3>
	<?PHP
//Meta Info
									//$_GET['id']=1;
									
									

									forEach($dwnlds as $row){
										echo sprintf("<div class='meta-info-unit'>
														<a href=\"%s\"><div class='icon'>
															<img src='images/bg.png' width='30px' alt='' class='float-left mr-2'>
															<p style='width: 230px;'>%s</p>
														</div></a>
														<div style='margin-top: -12px;'><i class='fa fa-download float-left txtpurpleans' aria-hidden='true'></i></div>
														</div>",$row['href'],$row['title']);
									}
?>
	<ul class="share-list">
									<li><span>Share:</span></li>
									<li><a href="#" class="facebook"><i class="fa fa-facebook-f"></i></a></li>
									<li><a href="#" class="twitter"><i class="fa fa-twitter"></i></a></li>
									<li><a href="#" class="google"><i class="fa fa-google-plus"></i></a></li>
									<li><a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
								</ul>
							</div>
							<!-- <div class="widget profile-widget">
								<div class="top-part">
									<img src="upload/teachers/teacher4-thumb.jpg" alt="Leslie Williams">
									<div class="name">
										<h3>Leslie Williams</h3>
										<span class="job-title">Math</span>
									</div>
								</div>
								<div class="content">
									<p>Donec tortor massa, dapibus sit amet massa ut, tincidunt dapibus neque. Morbi ac mauris lorem.</p>
									<a href="single-teacher.html" class="text-link">View full profile</a>
								</div>
							</div> -->
						</div>
					</div>
																		
							</div>				
					</div>				
		</section>
		<!-- End single-course section -->
		<section class="testimonial-section" style="background-color: #fff;">
			<div class="container">
				<div class="testimonial-box owl-wrapper">
					
					<div class="owl-carousel owl-theme" data-num="1" style="opacity: 1; display: block;">
					
						<div class="owl-wrapper-outer"><div class="owl-wrapper" style="width: 9648px; left: 0px; display: block; transition: all 800ms ease 0s; transform: translate3d(-2412px, 0px, 0px);"><div class="owl-item" style="width: 1206px;"><div class="item">
							<div class="testimonial-post">
								<p> “Design-driven, customized and reliable solution for your token development and management system to automate sales processes.”</p>
								<div class="profile-test">
									<div class="avatar-holder">
										<img src="upload/testimonials/testimonial-avatar-1.jpg" alt="">
									</div>
									<div class="profile-data">
										<h2>Nicole Alatorre</h2>
										<p>Designer</p>
									</div>
								</div>
							</div>
						</div></div><div class="owl-item" style="width: 1206px;"><div class="item">
							<div class="testimonial-post">
								<p> “Design-driven, customized and reliable solution for your token development and management system to automate sales processes.”</p>
								<div class="profile-test">
									<div class="avatar-holder">
										<img src="upload/testimonials/testimonial-avatar-2.jpg" alt="">
									</div>
									<div class="profile-data">
										<h2>Nicole Alatorre</h2>
										<p>Designer</p>
									</div>
								</div>
							</div>
						</div></div><div class="owl-item" style="width: 1206px;"><div class="item">
							<div class="testimonial-post">
								<p> “Design-driven, customized and reliable solution for your token development and management system to automate sales processes.”</p>
								<div class="profile-test">
									<div class="avatar-holder">
										<img src="upload/testimonials/testimonial-avatar-3.jpg" alt="">
									</div>
									<div class="profile-data">
										<h2>Nicole Alatorre</h2>
										<p>Designer</p>
									</div>
								</div>
							</div>
						</div></div><div class="owl-item" style="width: 1206px;"><div class="item">
							<div class="testimonial-post">
								<p> “Design-driven, customized and reliable solution for your token development and management system to automate sales processes.”</p>
								<div class="profile-test">
									<div class="avatar-holder">
										<img src="upload/testimonials/testimonial-avatar-4.jpg" alt="">
									</div>
									<div class="profile-data">
										<h2>Nicole Alatorre</h2>
										<p>Designer</p>
									</div>
								</div>
							</div>
						</div></div></div></div>
					<div class="owl-controls clickable"><div class="owl-pagination"><div class="owl-page"><span class=""></span></div><div class="owl-page"><span class=""></span></div><div class="owl-page active"><span class=""></span></div><div class="owl-page"><span class=""></span></div></div><div class="owl-buttons"><div class="owl-prev"></div><div class="owl-next"></div></div></div></div>
				</div>
			</div>
		</section>
		<?PHP include "src/footer.html";?>
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
</body>
</html>



	
	<?PHP 
	function review_handler($rating,$review,$uid,$courseid)
	{

	if(is_number($rating) && $review!="" && is_number($uid) && is_number($courseid))
	{
	try{
	$sql=("INSERT INTO review(cid,uid,review,rating) VALUES(%s,%s,\"%s\",%s)");
	$_SERVER["db"]->query($sql);
	echo "Review Submited";
	}
	catch(Exception $e)
	{
	echo $e->getMessage();
	}

	
		echo "<script>alert('Your Review Submitted Successfully')</script>";
	}
	
	die();}
	?>