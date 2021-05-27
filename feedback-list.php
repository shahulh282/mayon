<?PHP
require_once  "src/dbms.php";
require_once  "src/User.php";	

function find_approved_feedbacks($pageNo)
	{
	$sql=sprintf("SELECT id from feedbacks where approve=1 ORDER BY approve LIMIT %s,%s",($pageNo-1)*6,6);
	$_SERVER['db']->query($sql);
	$feedbacks=$_SERVER['db']->recordsArray(false);
	return $feedbacks;
	}

function put_feedback($feedbackid)
{
	if($feedbackid=="default")
	return sprintf("<div class='col-12 my-3'>
						<div class='col-12 p-2' style='background-color: #fff;border-radius: 15px;'>
							<p class='px-3 center'><b>%s</b> </p>
							<h2 class='text-right px-3'><b>%s</b><br>
							<span style='font-size: 14px;'>%s  %s</span></h2>
						</div>
					</div>","No Feedbacks Yet","","","");
	$sql=sprintf("select uid,class,board,subject,feedback from feedbacks where id =%s",$feedbackid);	
	$_SERVER['db']->query($sql);
	$record=$_SERVER['db']->recordsArray(false);
	$name=$record[0]['uid'];
	require_once "src/User.php";
	$name=User::getUserById($name)->getName();
	return sprintf("<div class='col-12 my-3'>
						<div class='col-12 p-2' style='background-color: #fff;border-radius: 15px;'>
							<p class='px-3 text-left'><b>%s</b> </p>
							<h2 class='text-right px-3'>-<b>%s</b><br>
							<span style='font-size: 14px;'>%s - %s</span></h2>
						</div>
					</div>",$record[0]['feedback'],$name,$record[0]['class'],$record[0]['board'],$record[0]['subject']);
	
}

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
		<!-- Header
		    ================================================== -->
			<?PHP include "src/header.php";?>
		<!-- End Header -->
		<section class="page-banner-section">
			<div class="container">
				<h1>Student Feedback</h1>
				<ul class="page-depth">
					<li><a href="index.php">Home</a></li>
					<li><a href="feedback-list.php">Student Feedback</a></li>
				</ul>
			</div>
		</section>
		<section class="page-banner-section">
			<div class="container">
				<div class="row">

				<!--<div class="col-12 my-3">
						<div class="col-12 p-2" style="background-color: #fff;border-radius: 15px;">
							<p class="px-3 text-left"><b>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Minus tenetur consequuntur voluptas labore dicta iusto sit fugiat repellendus. Aut, ipsam accusamus! Dolores consequatur quisquam aliquid necessitatibus vel qui! Rerum illo veniam atque, unde aliquam possimus consectetur fugiat reiciendis error accusamus temporibus sit laboriosam aperiam corporis nihil voluptatibus fugit harum quasi?</b> </p>
							<h2 class="text-right px-3">- <b>S. xyx</b>  <br>
							<span style="font-size: 14px;">XII Maths - CBSE </span></h2>
						</div>
					</div>
					<div class="col-12 my-3">
						<div class="col-12 p-2" style="background-color: #fff;border-radius: 15px;">
							<p class="px-3 text-left"><b>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Minus tenetur consequuntur voluptas labore dicta iusto sit fugiat repellendus. Aut, ipsam accusamus! Dolores consequatur quisquam aliquid necessitatibus vel qui! Rerum illo veniam atque, unde aliquam possimus consectetur fugiat reiciendis error accusamus temporibus sit laboriosam aperiam corporis nihil voluptatibus fugit harum quasi?</b> </p>
							<h2 class="text-right px-3">- <b>S. xyx</b>  <br>
							<span style="font-size: 14px;">XII Maths - CBSE </span></h2>
						</div>
					</div>
					<div class="col-12 my-3">
						<div class="col-12 p-2" style="background-color: #fff;border-radius: 15px;">
							<p class="px-3 text-left"><b>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Minus tenetur consequuntur voluptas labore dicta iusto sit fugiat repellendus. Aut, ipsam accusamus! Dolores consequatur quisquam aliquid necessitatibus vel qui! Rerum illo veniam atque, unde aliquam possimus consectetur fugiat reiciendis error accusamus temporibus sit laboriosam aperiam corporis nihil voluptatibus fugit harum quasi?</b> </p>
							<h2 class="text-right px-3">- <b>S. xyx</b>  <br>
							<span style="font-size: 14px;">XII Maths - CBSE </span></h2>
						</div>
					</div>
					<div class="col-12 my-3">
						<div class="col-12 p-2" style="background-color: #fff;border-radius: 15px;">
							<p class="px-3 text-left"><b>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Minus tenetur consequuntur voluptas labore dicta iusto sit fugiat repellendus. Aut, ipsam accusamus! Dolores consequatur quisquam aliquid necessitatibus vel qui! Rerum illo veniam atque, unde aliquam possimus consectetur fugiat reiciendis error accusamus temporibus sit laboriosam aperiam corporis nihil voluptatibus fugit harum quasi?</b> </p>
							<h2 class="text-right px-3">- <b>S. xyx</b>  <br>
							<span style="font-size: 14px;">XII Maths - CBSE </span></h2>
						</div>
					</div>
					<div class="col-12 my-3">
						<div class="col-12 p-2" style="background-color: #fff;border-radius: 15px;">
							<p class="px-3 text-left"><b>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Minus tenetur consequuntur voluptas labore dicta iusto sit fugiat repellendus. Aut, ipsam accusamus! Dolores consequatur quisquam aliquid necessitatibus vel qui! Rerum illo veniam atque, unde aliquam possimus consectetur fugiat reiciendis error accusamus temporibus sit laboriosam aperiam corporis nihil voluptatibus fugit harum quasi?</b> </p>
							<h2 class="text-right px-3">- <b>S. xyx</b>  <br>
							<span style="font-size: 14px;">XII Maths - CBSE </span></h2>
						</div>
					</div>
					<div class="col-12 my-3">
						<div class="col-12 p-2" style="background-color: #fff;border-radius: 15px;">
							<p class="px-3 text-left"><b>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Minus tenetur consequuntur voluptas labore dicta iusto sit fugiat repellendus. Aut, ipsam accusamus! Dolores consequatur quisquam aliquid necessitatibus vel qui! Rerum illo veniam atque, unde aliquam possimus consectetur fugiat reiciendis error accusamus temporibus sit laboriosam aperiam corporis nihil voluptatibus fugit harum quasi?</b> </p>
							<h2 class="text-right px-3">- <b>S. xyx</b>  <br>
							<span style="font-size: 14px;">XII Maths - CBSE </span></h2>
						</div>
					</div>-->
					
<?PHP
if(isset($_GET["page"]))
{
	$fbs=find_approved_feedbacks($_GET["page"]);
	if(count($fbs)==0)
	$fbs=find_approved_feedbacks(1);
	
}

else
$fbs=find_approved_feedbacks(1);
foreach($fbs as $fb)
{
//var_dump();
echo put_feedback($fb["id"]);
}
?>
					
				</div>
			</div>
		</section>



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
	
</body>
</html>