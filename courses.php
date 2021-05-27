<?PHP 
include "./config.php";
include "src/User.php";
include_once "./includes/access.inc.php";
include "./includes/functions.inc.php";
include "src/dbms.php";

include "src/course-module.php";
include "pager/Paginated.php";
include "pager/DoubleBarLayout.php";
include "pager/TrailingLayout.php";

$idset=array();
$page=isset($_GET["page"])?$_GET["page"]:1;
$resultset=search(isset($_GET["search"])?$_GET["search"]:"",$page);

function regorbuilder($keyword)
{
$keyword=str_replace(" ","|",$keyword);
return "REGEXP\"{$keyword}\"";
}


function search($query,$page=1)
{
	$q=explode(":",$query);
    $term=end($q);
    $sql="";
	if(preg_match("/catagory:/i",$query)){
		$sql.=sprintf("SELECT count(courseid) as count  from course where status=1 and lower(board)=\"%s\"",strtolower($term));
		$filter=1;
		
	}
		
	else if(preg_match("/tag:/i",$query)){
		$sql.=sprintf("SELECT count(courseid) as count from course where status=1 and lower(subject)=\"%s\"",strtolower($term));
		$filter=2;
		
	}
	else if(isset($term) && isset($_GET["search"]))
	{
		$sql.=sprintf("SELECT count(courseid) as count from course where status=1 and class %s UNION
					   SELECT count(courseid) as count from course where status=1 and lower(subject) %s UNION
					   SELECT count(courseid) as count from course where status=1 and lower(board) %s UNION
					   SELECT count(courseid) as count from course where status=1 and lower(coursetitle) %s UNION
					   SELECT count(courseid) as count from course where status=1 and lower(description) %s ",regorbuilder($term),
					   regorbuilder($term),regorbuilder($term),regorbuilder($term),regorbuilder($term));
					   //echo $sql;		
					   $filter=3;
	}
	else
	{
		$sql.=sprintf("SELECT count(courseid) as count from course where status=1");
		//echo "Triggerd Normal";
		$filter=4;
	}
	$_SERVER["db"]->query($sql);
	
	$total_records=$_SERVER["db"]->data();

	switch($filter)
	{	
		case 1:
			$_SERVER["db"]->query(sprintf("SELECT courseid from course where status=1 and  lower(board)=\"%s\" order by courseid",$term,($page-1)*9));
			//echo $filter;
			break;	
		case 2:
			$_SERVER["db"]->query(sprintf("SELECT courseid from course where status=1 and lower(subject)=\"%s\" order by courseid",$term,($page-1)*9));	
			//echo $filter;
			break;
		case 3:
			$_SERVER["db"]->query(sprintf("
			SELECT courseid  from course where status=1 and  class %s UNION
			SELECT courseid  from course where status=1 and lower(subject) %s UNION
			SELECT courseid  from course where status=1 and lower(board) %s UNION
			SELECT courseid  from course where status=1 and lower(coursetitle) %s UNION
			SELECT courseid  from course where status=1 and lower(description) %s",
			regorbuilder($term),
			regorbuilder($term),
			regorbuilder($term),
			regorbuilder($term),
			regorbuilder($term)));
			//echo $filter;
			break;
		default:
			$_SERVER["db"]->query("SELECT courseid from course where status=1 order by lastupdated  ");
			//echo $filter;
	}


	$data=array(
		"idset"=>$_SERVER["db"]->recordsArray(false),
		"page"=>$page,
		"iTotalRecords"=>$total_records,
		"total_pages"=>ceil($total_records/9)
	);	
	return $data ;
}

?>

<!doctype html>

<html lang="en" class="no-js">
<head>
	<title>Courses</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,400i,500,500i,600,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/studiare-assets.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/fonts/font-awesome/font-awesome.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="assets/css/fonts/elegant-icons/style.css" media="screen">
	<link rel="stylesheet" type="text/css" href="assets/css/fonts/iconfont/material-icons.css" media="screen">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="shortcut icon" href="assets/images/logo.png">
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
			<?PHP include "src/header.php"; ?>
		<!-- page-banner-section ================================================== -->
		<section class="page-banner-section">
			<div class="container">
				<h1>Courses</h1>
				<ul class="page-depth">
					<li><a href="index.php">Home</a></li>
					<li><a href="courses.php">Courses</a></li>
				</ul>
			</div>
		</section>
		<!-- End page-banner-section -->

		<!-- blog-section 
			================================================== -->
		<section class="blog-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-5">
						<div class="sidebar">
							<div class="search-widget widget">
								<form id='search' class="search-form" method='GET' action='courses.php'>
									<input type="search" name='search' class="search-field" placeholder="Enter keyword...">
									<input type="hidden" name="page" value="1"/>
									<button type="submit"  class="search-submit">
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
										$sql=sprintf("SELECT DISTINCT board as matric from course");
										$_SERVER["db"]->query($sql);
										$category=$_SERVER["db"]->recordsArray(false);
										foreach($category as $row)
										{
											$data=strtoupper($row['matric']);
											echo "<li>
													<a href=\"courses.php?search=catagory:{$row['matric']}&page=1\" >{$data}</a>
												  </li>";
										}
									}
									catagory_list();
								?>
								</ul>
							</div>

							<div class="tags-widget widget">
								<h2>Tags</h2>
								<ul class="tags-list">
								<?PHP 
									
									function tag_list()
									{
									$sql=sprintf("SELECT DISTINCT subject from course");
									$_SERVER["db"]->query($sql);
									$taglist=$_SERVER["db"]->recordsArray(false);
									foreach($taglist as $row)
										echo "<li><a href='courses.php?search=tag:{$row['subject']}'>{$row['subject']}</a></li>";
									}
									tag_list();
									?>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-9 col-md-7">
						<div class="blog-">
							<div class="row">
					
<?PHP 		
$limit=count($resultset['idset'])>9?9:count($resultset["idset"]);
//print_r($resultset);
if(count($resultset["idset"])>0)
{	

	$var=$pagedResults=new Paginated($resultset["idset"],$limit,$resultset["page"]);	
	while($row=$pagedResults->fetchPagedRow())
	{	
	if(isset($row["courseid"]))
	echo course_template($row["courseid"],1);
	}
}
else
{
	$var=$pagedResults=new Paginated(array(),$limit,$resultset["page"]);
}
echo "<h1>NO Results Found</h1>";

?>
</div>

								<ul class="page-pagination">
									<?PHP
									$pagedResults->setLayout(new DhruvLayout());
									echo "<ul class='page-pagination'>";
									if(isset($_GET["search"]))
									echo $pagedResults->fetchPagedNavigation("&search=".$_GET["search"]);
									else
									echo $pagedResults->fetchPagedNavigation();
									echo "</ul>";
								?>
								</ul>

						
					</div>

				</div>
						
			</div>
		</section>
		<!-- End blog section -->
		<section class="testimonial-section" style="background-color: #fff;">
			<div class="container">
				<div class="testimonial- owl-wrapper">
					
					<div class="owl-carousel owl-theme" data-num="1" style="opacity: 1; display: block;">
					
						<div class="owl-wrapper-outer"><div class="owl-wrapper" style="width: 9648px; left: 0px; display: block; transition: all 0ms ease 0s; transform: translate3d(0px, 0px, 0px);"><div class="owl-item" style="width: 1206px;"><div class="item">
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
					<div class="owl-controls clickable"><div class="owl-pagination"><div class="owl-page active"><span class=""></span></div><div class="owl-page"><span class=""></span></div><div class="owl-page"><span class=""></span></div><div class="owl-page"><span class=""></span></div></div><div class="owl-buttons"><div class="owl-prev"></div><div class="owl-next"></div></div></div></div>
				</div>
			</div>
		</section>
		<!-- footer 
			================================================== -->
			<footer>
				<div class="container">
	
					<div class="up-footer">
						<div class="row">
	
							<div class="col-lg-4 col-md-6">
								<div class="footer-widget text-widget">
									<a href="index.php" class="footer-logo" style="color: #fff;font-size: 18px;"><img src="images/logowht.png" width="80px" alt="" ><span class="ml-2">Maths Physcics</span></a>
  									<ul>
										<li>
											<div class="contact-info-icon">
												<i class="material-icons">location_on</i>
											</div>
											<div class="contact-info-value">127 Elizabeth Street, NY New York</div>
										</li>
										<li>
											<div class="contact-info-icon">
												<i class="material-icons">phone_android</i>
											</div>
											<div class="contact-info-value">+91-11-3097-0508</div>
										</li>
									</ul>
								</div>
							</div>
	
							<div class="col-lg-4 col-md-6">
								<div class="footer-widget quick-widget">
									<h2>Quick Links</h2>
									<ul class="quick-list">
										<li><a href="index.php">Home</a></li>
										<li><a href="contact.php">Contact</a></li>
										<li><a href="communitypage.php">Community</a></li>
										<li><a href="about.php">About Us</a></li>
										<li><a href="courses.php">Courses</a></li>
										<li><a href="tuition.php">Tuition</a></li>
										<li><a href="feedback-form.php">Add Feedback</a></li>
									</ul>
								</div>
							</div>
	
							<div class="col-lg-4 col-md-6">
								<div class="footer-widget subscribe-widget">
									<h2>Newsletter</h2>
									<p>Don’t miss anything, sign up now and keep informed about our company.</p>
									<div class="newsletter-form">
										<input class="form-control" type="email" name="EMAIL" placeholder="Enter Your E-mail" required="">
										<input type="submit" value="Subscribe">
									</div>
								</div>
							</div>
	
						</div>
					</div>
	
				</div>
	
				<div class="footer-copyright copyrights-layout-default">
					<div class="container">
						<div class="copyright-inner">
							<div class="copyright-cell"> &copy; 2021 <span class="highlight">Dhruv Maths Physcis</span>. All Rights Reserved.</div>
							<div class="copyright-cell">
								<ul class="studiare-social-links">
									<li><a href="#" class="facebook"><i class="fa fa-facebook-f"></i></a></li>
									<li><a href="#" class="twitter"><i class="fa fa-twitter"></i></a></li>
									<li><a href="#" class="google"><i class="fa fa-google-plus"></i></a></li>
									<li><a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
	
			</footer>
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
	
	</script>
</body>
</html>