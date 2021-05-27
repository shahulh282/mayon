<?PHP 
//include "src/mail_function.php"
//$_POST["name"]="Hariharan";
//$_POST["mail"]="smarthariharan28@gmail.com";
//$_POST["number"]="8807290807";
//$_POST["comment"]="Hi You Are Doing Well";
//$_POST["submit"]="contact";

if(isset($_POST["submit"]) && $_POST["submit"]="contact")
	if(isset($_POST['name'],$_POST['mail'],$_POST['number'],$_POST['comment']))
	{
		header("Content-Type:application/json");

		$name 	 = $_POST['name'];
		$mail 	 = $_POST['mail'];
		$comment = $_POST['comment'];
		$number  = $_POST['number'];	
		
		if($name == '') 
				{
					echo json_encode(array('info' => 'error', 'message' => "Please enter your name."));
					exit();
				} 
			else if($mail == '' or preg_match("/[a-z][a-z.0-9]+@[a-z.0-9]/",$mail) == false)
				{
				echo json_encode(array('info' => 'error', 'message' => "Please enter valid e-mail."));
				exit();
				} 
			else if ($number ==''){
				echo json_encode(array('info' => 'error', 'message' => "Please enter valid Number."));
				exit();
			}
			else if($comment == '')
				{
				echo json_encode(array('info' => 'error', 'message' => "Please enter your message."));
				exit();
				}
			else 
				{
				//Send Mail
				
				include "src/mail_function.php";
				$to = $_POST["mail"];
				$subject = "New Insight" . ' ' . $name;
				$message = '
				<html>
				<head>
			  	<title>Mail from '. $name .'</title>
				</head>
				<body>
			  	<table style="width: 500px; font-family: arial; font-size: 14px;" border="1">
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">Name:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $name .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">E-mail:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $mail .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">Subject:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $subject .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">Comment:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $comment .'</td>
				</tr>
			  	</table>
				</body>
				</html>';

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$headers .= 'From: ' . $mail . "\r\n";

				send_mail($to,$subject,$message,$headers);
				echo json_encode(array("info"=>"success","message"=>"Form Submitted Successfully"));
				exit();
			
			}
		
	echo  json_encode(array("info"=>"error","message"=>"Please Fill Out All Fields"));
	exit();
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
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/logo.png">
    <link rel="apple-touch-icon-precomposed" href="assets/images/logo.png">

</head>
<body>

	<!-- Container -->
	<div id="container">
		<!-- Header================================================== -->
		<?PHP include "src/header.php";?>
		<!-- End Header -->

		<!-- contact-section================================================== -->
		<section class="contact-section">
			<div class="container">
				<div class="contact-box">
					<h1>Get in Touch</h1>
					<p>Someone finds it difficult to understand your creative idea? There is a better visualisation. Share your views with us, we’re looking forward to hear from you.</p>
					<form id="contact-form" novalidate>
						<div class="row">
							<div class="col-md-6">
								<label for="name">Your Name (required)</label>
								<input name="name" id="name" type="text">
							</div>
							<div class="col-md-6">
								<label for="mail">Your Email (required)</label>
								<input name="mail" id="mail" type="text">
							</div>
						</div>
						<label for="number">Your Phone Number (required)</label>
						<input name="number" id="tel-number" type="text">
						<label for="comment">Your Message (required)</label>
						<textarea name="comment" id="comment"></textarea>
						<button type="submit" id="submit_contact" name='submit' value='contact'>Submit Message</button>
						<div id="msg" style='display:none'class="message"></div>
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

		<!-- footer ================================================== -->
			<?PHP include "src/footer.html"; ?>
		<!-- End footer -->

	</div>
	<!-- End Container -->

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/studiare-plugins.min.js"></script>
	<script src="assets/js/jquery.countTo.js"></script>
	
	<script src="assets/js/popper.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en"></script>
	<script src="assets/js/gmap3.min.js"></script>
	<script src="assets/js/script.js"></script>
	<script>

			$("#contact-form").on('submit',(e)=>{
				e.preventDefault();
				$.ajax({
					type:"POST",
					data:{
						name:$("#contact-form [name='name']").val(),
						mail:$("#contact-form [name='mail']").val(),
						number:$("#contact-form [name='number']").val(),
						comment:$("#contact-form [name='comment']").val(),
						submit:"contact"
					},
					url:'contact.php',
					success:(data)=>
					{

						$("#msg").css('display','block');
						if(data.info=='success'){
						$("#msg").text(data.message);
						
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
			</script>
</body>
</html>