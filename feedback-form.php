<?PHP
include "src/dbms.php";
require_once "src/User.php";
session_start();

//Getting Matric from Syllabus Table
//Getting 




if(isset($_SESSION["currentuser"]) && $_SESSION["currentuser"]!=null)
	if(isset($_POST['board'], $_POST['class'] , $_POST['subject'] , $_POST['comment'] ) ){

	if($_POST['board']=='' || $_POST['class']=='' || $_POST['subject']=='' || $_POST['comment']=='')
		{
			echo json_encode(array( "info" => "error" ,"message"=>"Please Fill Out all Fields"));
			exit();
		}
		else
		{
			$sql=sprintf("INSERT INTO 
			feedbacks(uid,board,class,subject,feedback)
			VALUES(%s,\"%s\",\"%s\",\"%s\",\"%s\")
			",$_SESSION['currentuser']->getUid(),$_POST['board'],$_POST['class'],$_POST['subject'],$_POST['comment']);
			$_SERVER['db']->query($sql);
			echo json_encode(array( "info" => "success" ,"message"=>"Your Feedback Submitted Successfully!"));
			exit();
		}
		
	}
	



if(isset($_SESSION['currentuser']))
{

}
else{
	echo "<script>
	alert('Please Login to submit your feedback')
	window.onload=()=>{
	window.location.href='login.php?redirect=feedback-form.php';
	}
	</script>";
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
	
	<link rel="stylesheet" href="assets/css/studiare-assets.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/fonts/font-awesome/font-awesome.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="assets/css/fonts/elegant-icons/style.css" media="screen">
	<link rel="stylesheet" type="text/css" href="assets/css/fonts/iconfont/material-icons.css" media="screen">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
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
		<?PHP include "src/header.php"; ?>
		<!-- contact-section 
			================================================== -->
		<section class="contact-section">
			<div class="container">
				<div class="contact-box">
					<h1>Feedback Form</h1>
					<p>Kindly enter your feedback on us</p>
					<div class="student-details text-left my-2">
						<h3><b>Name-</b> <?PHP echo $_SESSION['currentuser']->getName(); ?> <!--W.xyz--></h3>
						<h3><b>Phone-</b><?PHP echo $_SESSION['currentuser']->getNumber();?><!--+91 01234 546565--></h3>
						<h3><b>Email-</b><?PHP echo $_SESSION['currentuser']->getEmail(); ?> <!--xyz@example.com--></h3>
					</div>			
					<form id="contact-form" action='feedback-form.php' method='post'>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="">Choose your Metric</label>
								<select style="color:black;" class="form-control" name="board" id="class" placeholder="Class" required="">
									<option value="">Choose any option</option>
									<option value="cbse" style="color:black;">CBSE</option>
									<option value="gcse" style="color:black;">GCSE</option>
									<option value="state" style="color:black;">State Board</option>
									<option value="icse" style="color:black;">ICSE</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="">Choose your Class</label>
								<select style="color:black;" class="form-control" name="class" id="class" required="">
									<option value="">Choose any option</option>
									<option value="12" style="color:black;">XII</option>
									<option value="11" style="color:black;">XI</option>
									<option value="10" style="color:black;">X</option>
									<option value="9" style="color:black;">IX</option>
								</select>
							</div>
						</div>
						<div class="mb-3">
							<label for="tel-number">Subject </label>
							<select style="color:black;" class="form-control" name="subject" id="class" required="">
								<option value="">Choose any option</option>
								<option value="Math" style="color:black;">Math</option>
								<option value="Physics" style="color:black;">Physcics</option>
								<option value="Chemistry" style="color:black;">Chemistry</option>
								<option value="Biology" style="color:black;">Biology</option>
							</select>					
						</div>
						<label for="comment">Your Feedback</label>
						<textarea name="comment" id="comment"></textarea>
						<button name='submit' type="submit" id="submit_contact">Submit Message</button>
						<div id="msg" class="message"></div>
					</form>
				</div>
			</div>
		</section>
		<!-- End contact section -->

		<!-- contact-info-section 
			================================================== -->
		<section class="contact-info-section">
			<div class="container">
				<div class="contact-info-box">
					<div class="row">

						<div class="col-lg-4 col-md-6">
							<div class="info-post">
								<div class="icon">
									<i class="fa fa-envelope-o"></i>
								</div>
								<div class="info-content">
									<p>
										Tel: +1 (420) 899 4400 <br>
										E-Mail: <a href="#">hello@codebean.co</a>
									</p>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="info-post">
								<div class="icon">
									<i class="fa fa-map-marker"></i>
								</div>
								<div class="info-content">
									<p>
										6100 Wilshire Blvd 2nd Floor Los <br>
										Angeles CA
									</p>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="info-post">
								<div class="icon">
									<i class="fa fa-clock-o"></i>
								</div>
								<div class="info-content">
									<p>
										Our office is open:<br>
										Mon to Fri from 8am to 6pm
									</p>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</section>
		<!-- End contact-info section -->

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
										<input class="form-control" type="email" name="EMAIL" placeholder="Enter Your E-mail" required>
										<input id='subscribe_newsletter' type="submit" value="Subscribe">
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
	$(document).ready((e)=>
	{
		//Getting Matric List

		//Getting Class List 

		//Getting Subject List 

	});
	function printList(array,target)
	{
		
		array=array.map((e)=>{
			var k=$("<option>");
			k.attr("value",e);
			k.text(e)
			return k;
			})
			$("[name=\""+target+"\"]").html("<option value='none'>Choose Any Option</option>");
			array.forEach((e)=>
			{
				$("[name=\""+target+"\"]").append(e);
			})
			

	}
	var submitContact = $('#submit_contact'),
		message = $('#msg');

	submitContact.on('click', function(e){
		e.preventDefault();

		var $this = $(this);
		
		$.ajax({
			type: "POST",
			url: 'feedback-form.php',
			dataType:'json',
			cache: false,
			data: $('#contact-form').serialize(),
			success: function(data) {

				if(data.info !== 'error'){
					console.log("postdata:"+data.postdata);
					$this.parents('form').find('input[type=text],textarea,select').filter(':visible').val('');
					message.hide().removeClass('alert-success').removeClass('alert-danger').addClass('alert-success').html(data.msg).fadeIn('slow').delay(5000).fadeOut('slow');
					message.text(data.message);
				} else {
					console.log("postdata"+data.postdata);
					message.hide().removeClass('alert-success').removeClass('alert-danger').addClass('alert-danger').html(data.msg).fadeIn('slow').delay(5000).fadeOut('slow');
					message.text(data.message);
				}
			}
		});
	});
	</script>
	
</body>
</html>