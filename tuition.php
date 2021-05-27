<?PHP 
include "dbms.php";
include "src/User.php";
include "src/mail_function.php";

session_start();
include "security.php";
function check_email($email){
	return (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email));
}


	
	
if(!empty($_POST))
{
	header("Content-type:application/json");	
	if(!empty($_POST['Metric']) && !empty($_POST['Class']) && !empty($_POST['Subject']))

			
				if(!empty($_POST['comment']))
					{
					$subject='Tuition Request From MathsPhysics.com';
					$content=$_POST['comment'];
					$_SERVER["db"]->query(sprintf("
					INSERT INTO 
					enquiry(uid,matric,class,subject,timestamp,status,enquiry) 
					VALUES(%s,\"%s\",\"%s\",\"%s\",CURRENT_TIMESTAMP,0,\"%s\")",
					$_SESSION["currentuser"]->getUid(),
					$_POST["Metric"],
				    $_POST["Class"],
					$_POST["Subject"],
					$_POST["comment"]
				));
					echo json_encode(array("info"=>"success","message"=>"Thank You For Contacting Us!"));
					exit();
					}
				else
					{
				 	echo json_encode(array("info"=>"error","message"=>"Please Fillout All Columns"));
					 exit();
					}
		else
		{
		echo json_encode(array("info"=>"error","message"=>"Fill All Details "));
		exit();
		}		
	
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
		<?PHP include "src/header.php"; ?>
		<!-- contact-section 
			================================================== -->
			<?PHP 
			
			
			
			$name="";
			$email="";
			$number="";
			if(isset($_SESSION['currentuser']))
			if($_SESSION['currentuser']!=null)
			{
				$name=$_SESSION["currentuser"]->getName();
				$number=$_SESSION["currentuser"]->getNumber();
				$email=$_SESSION["currentuser"]->getEmail();
			}
			


			?>
		<section class="contact-section">
			<div class="container">
				<div class="contact-box">
					<h1>Tuition</h1>
					<p>Clear your doubts and schedule your tuition</p>
					<div class="student-details text-left my-2">
						<h3><b>Name-</b><?PHP echo $name;  ?></h3>
						<h3><b>Phone-</b><?PHP echo $number; ?></h3>
						<h3><b>Email-</b><?PHP echo $email; ?></h3>
					</div>
					<form id="contact-form"  novalidate>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="">Choose your Metric</label>
								<input type='hidden' name='name'  value=<?PHP echo "\"".$name."\"";?>/>
								<input type='hidden' name='number' value=<?PHP echo "\"".$name."\"";?> />
								<input type='hidden' name='email' value=<?PHP echo "\"".$email."\"";?> />
								<select style="color:black;" class="form-control" name="Metric" id="class" placeholder="Class" required="">
									<option value="">Choose any option</option>
									<option value="CBSE" style="color:black;">CBSE</option>
									<option value="GCSE" style="color:black;">GCSE</option>
									<option value="STATE BOARD" style="color:black;">State Board</option>
									<option value="ICSE" style="color:black;">ICSE</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="">Choose your Class</label>
								<select style="color:black;" class="form-control" name="Class" id="class" required="">
									<option value="">Choose any option</option>
									<option value="12" style="color:black;">XII</option>
									<option value="11" style="color:black;">XI</option>
									<option value="10" style="color:black;">X</option>
									<option value=" 9" style="color:black;">IX</option>
								</select>
							</div>
						</div>
						<div class="mb-3">
							<label for="tel-number">Subject </label>
							<select style="color:black;" class="form-control" name="Subject" id="class" required="">
								<option value="">Choose any option</option>
								<option value="Math" style="color:black;">Math</option>
								<option value="Physcics" style="color:black;">Physcics</option>
								<option value="Chemistry" style="color:black;">Chemistry</option>
								<option value="Biology" style="color:black;">Biology</option>
							</select>					
						</div>
						<label for="comment">Your Message</label>
						<textarea name="comment" id="comment"></textarea>
						<button type="submit" id="submit_contact">Submit Message</button>
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
		<!-- Footer Section is moved  -->
		<?PHP include "src/footer.html" ?>
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
		/*---Ajax For Form Submission---*/
			$("#contact-form").on('submit',(e)=>{
				e.preventDefault();
				$.ajax({
					type:"POST",
					data:$("#contact-form").serialize(),
					url:'tuition.php',
					
					success:(data)=>
					{

						$("#msg").css('display','block');
						if(data.info=='success'){
						$("#msg").text(data.message);
						console.log($("#contact-form").serialize());
						$("#msg").attr('class',"alert alert-success");

						}
						else
						{
						$("#msg").text(data.message);
						$("#msg").attr('class',"alert alert-danger");
					}
						$("#msg").fadeToggle(4000);
					},
				})
			})
			

	/*---Ajax For Form Submission--end*/
	</script>
	
</body>
</html>