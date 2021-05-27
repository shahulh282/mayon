<?PHP 
include "./config.php";
include "src/dbms.php";
include "src/featured.php";
include "src/module.php";
include "src/course-module.php";
include "src/User.php";

include "includes/access.inc.php";
$title="Maths Physics | Home";
?>

<!doctype html>
<html lang="en" class="no-js">

<?PHP include "includes/student.head.inc.php"; 

if(isset($errorcode))
{
include "includes/error.inc.php";
include "includes/student.footer.inc.php";
include "includes/student.scripts.inc.php";
exit();
}
?>

<body>
	<!-- Container -->
	<div id="container">
		<!-- Start Header -->
		<?PHP include "src/header.php";?>
			<marquee style="padding-top: 8px;" attribute_name = "attribute_value"....more attributes>		
				<!---Banner Block-Start-->
				<?PHP	$bt=HompageBanner();echo $bt!=''?$bt:"Promotion Text";?>
				<!---Banner Block-End---->		
			</marquee>
		<!-- End Header -->

		<!--======================home-section============================ -->
		<section id="home-section" style="background: url(images/bg.png);background-repeat: no-repeat;background-size: cover;">
			<div class="container">
				<div class="row">
					<div class="col-12 mt-5 p-0">
						<div class="col-md-6 float-left">
							<a href="feedback-list.php">
								<img src="images/ico/1.png" class="float-left px-3 icohome" alt="">
								<h1 class="text-white float-left headtitle ">Student Feedback</h1>
							</a> <br>
							<?PHP 
							
							foreach(getFeaturedLinks() as $row)
								{
									echo feature($row);
								}
							
							?>

						</div>
						<div class="col-md-6 col-xs-12 float-left">
							<iframe height="300px" width="100%" src="https://www.youtube.com/embed/qz0aGYrrlhU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>

					<div class="col-md-12 mt-4">
						<div class="col-md-6 col-xs-6 float-left p-2" data-toggle="modal" data-target="#exampleModalCenter">
							<div class="maintile purple">
								<a href="" ><h2 class="text-white txt-titlee">CBSE Class XI & XII</h2></a>
							</div>
						</div>
						<div class="col-md-3 col-xs-6 float-left p-2" data-toggle="modal" data-target="#exampleModalCenter">
							<div class="maintile drkpurple">
								<a href=""><h2 class="text-white txt-titlee">CBSE Class IX & X</h2></a>
							</div>
						</div>
						<div class="col-md-3 col-xs-6 float-left p-2" data-toggle="modal" data-target="#exampleModalCenter">
							<div class="maintile deepdrkpurple">
								<a href=""><h2 class="text-white txt-titlee">CBSE Class IV to VIII</h2></a>
							</div>
						</div>
					</div>
					<div class="col-md-12 mt-0">
						<div class="col-md-3 col-xs-6 float-left p-2" data-toggle="modal" data-target="#exampleModalCenter">
							<div class="maintile grrey">
								<a href=""><h2 class="text-white txt-titlee">AS/A Levels</h2></a>
							</div>
						</div>
						<div class="col-md-3 col-xs-6 float-left p-2" data-toggle="modal" data-target="#exampleModalCenter">
							<div class="maintile bluee">
								<a href=""><h2 class="text-white txt-titlee">GCSE – IX & X</h2></a>
							</div>
						</div>
						<div class="col-md-6 col-xs-6 float-left p-2" data-toggle="modal" data-target="#exampleModalCenter">
							<div class="maintile oorange">
								<a href=""><h2 class="text-white txt-titlee">GCSE IV to VIII</h2></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End home section -->
		<!-- popular-courses-section 
			================================================== -->
			<section class="popular-courses-section">
			<div class="container">
				<div class="title-section">
					<div class="left-part">
						<span style="color: #8845F3;">Downloads</span>
						<h1>Popular Downloads</h1>
					</div>
					<div class="right-part">
					</div>
				</div>

				<div class="popular-courses-box">
				
					<div class="row">
						
													
					<?PHP 
					$_SERVER["db"]->query("SELECT id from downloads where publish=1");
					$popular=$_SERVER["db"]->recordsArray(false);
					forEach($popular as $dwnlds)
						echo popularCourse($dwnlds)
					?>
					</div>
				</div>
		</section>		


		<section class="popular-courses-section" style="background-color: #f8f9fa;">
			<div class="container" style="background-color: #f8f9fa;">
				<div class="title-section" >
					<div class="left-part">
						<span>Community</span>
						<h1>Brainstrom Room</h1>
					</div>
					<div class="right-part">
						<a class="button-one" href="communitypage.php">View All Questions</a>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
					<?PHP
								//Events Latest Posts 
								$sql=sprintf("select pid,
											  DAY(ptimestamp) as day,
											  ptimestamp as pt,
											  ptitle as title,
											  0 as answers
											  from posts where pstatus=1 order by ptimestamp DESC LIMIT 0,6");
								$_SERVER["db"]->query($sql);
								$p=$_SERVER["db"]->recordsArray(false);
								$pcount=count($p);
								$chunk_of_3=array_chunk($p,3);
								
								forEach($chunk_of_3 as $chunk)
									{
									echo "<div class='col-lg-6 float-left'>
													<div class='events-box'>";
										
										foreach($chunk as $q)
											echo brain_strome($q);
											
									echo "</div></div>";
									}
								function brain_strome($q)
								{
									$_SERVER["db"]->query(sprintf("SELECT count(*) from comments where pid=%s",$q["pid"]));	
									$q["answers"]=$_SERVER["db"]->data();
									return  
								"<div class='events-post'>
									<div class='event-inner-content'>
										<div class='top-part'>
											<div class='date-holder'>
												<div class='date'>
													<span class='date-day'>".$q["day"]."</span>
													<span class='date-month'>".date("M",strtotime($q["pt"]))."</span>
												</div>
											</div>
											<div class='content'>
												<h2 class='title'><a href='single-post.php?id=".$q['pid']."'>".$q["title"]."</a></h2>
												<ul class='list-unstyled mt-3 comunityul'>
													<li class='float-left mr-3'><a href='single-post.php?id=".$q['pid']."' class='txtpurpleans'> ".$q["answers"]." Answers</a></li>
													<li class='float-left mr-3'><a href='single-post.php?id=".$q['pid']."' class='drkkyellow'>Comment</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>";
								}
							
								?>
					
				</div>
			</div>
		</section>
				<section class="testimonial-section"  style="background-color: #fff;">
					<div class="container">
						<div class="testimonial-box owl-wrapper">
							
							<div class="owl-carousel" data-num="1">
							
								<div class="item">
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
								</div>
							
								<div class="item">
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
								</div>
							
								<div class="item">
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
								</div>
							
								<div class="item">
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
								</div>
		
							</div>
						</div>
					</div>
				</section>
				<!-- End testimonial section -->
		
		<!--new Enquiry Model-->
		<!-- Modal -->
		<div style='position:relative;z-index:99999 !important;'class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding:5px 12px;">
					<span aria-hidden="true" style="font-size:30px;">&times;</span>
				</button>
				</div>
				<div class="modal-body">
					<h1 class="text-center">Please select the Class and subject</h1>
					<div class="col-3 float-left">
						<div class="form-group">
							<label for="exampleFormControlSelect1"></label>
							<select class="form-control" id="exampleFormControlSelect1">
							  <option>CBSE</option>
							  <option>ICSE</option>
							  <option>State Board</option>
							</select>
						  </div>
					</div>
					<div class="col-3 float-left">
						<div class="form-group">
							<label for="exampleFormControlSelect1"></label>
							<select class="form-control" id="exampleFormControlSelect1">
							  <option>XI</option>
							  <option>XII</option>
							  <option>X</option>
							</select>
						  </div>
					</div>
					<div class="col-6 float-left">
						<div class="form-group">
							<label for="exampleFormControlSelect1"></label>
							<select class="form-control" id="exampleFormControlSelect1">
							  <option>Maths</option>
							  <option>Physcics</option>
							  <option>Chemistry</option>
							</select>
						  </div>
					</div>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn purple text-white m-auto "><a href="single-course.php" style="color: #fff;">Submit</a> </button>
				</div>
			</div>
			</div>
		</div>
		<div class="modal fade" id="enquirypopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding:5px 12px;">
					<span aria-hidden="true" style="font-size:30px;">&times;</span>
				</button>
				</div>
				<div class="modal-body">
					<form action="src/upload.php?question" id='question_upload' enctype='multipart/form-data' method='post'>			
					<h1 class="text-center">Ask Us Your Question</h1>
					<div class="form-group col-sm-12">
						<input type="file"  class="form-control" name="attachment" id="attachment" placeholder="Attach image,pdf,docs">
					</div>
					
					<div class="form-group col-sm-12">
						<textarea class="form-control" id="question" name="question" rows="4" placeholder="Enter Question"></textarea>
					</div>
				</div>
				<div class="modal-footer">
				<button id='send_question' type="submit" class="btn purple text-white m-auto ">Submit</button>
				</form>
				</div>
			</div>
			</div>
		
		</div>
		</div>
		<?PHP
		include "src/footer.html";
		?>
		</div>

		
	<!-- End Container -->

	
	<script src="js/studiare-plugins.min.js"></script>
	<script src="js/jquery.countTo.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/gmap3.min.js"></script>
	<script src="js/script.js"></script>
	<script>   
	
	 	$(window).load(function(){
        	setTimeout(function() 
			{
               $('#enquirypopup').modal('show');
        	}, 3000);
			});
			$(".top-part div.content").css("width","100%");
			/*$(window).on('DomContentLoaded',()=>{
				$(".modal-backdrop").css("{'position':'relative','z-index':'99888 !important'}");
			})*/
	</script>

	<script>
		var tpj=jQuery;
		var revapi202;
		tpj(document).ready(function() {
			if (tpj("#rev_slider_202_1").revolution == undefined) {
				revslider_showDoubleJqueryError("#rev_slider_202_1");
			} else {
				revapi202 = tpj("#rev_slider_202_1").show().revolution({
					sliderType: "standard",
					jsFileLocation: "js/",
					dottedOverlay: "none",
					delay: 5000,
					navigation: {
						keyboardNavigation: "off",
						keyboard_direction: "horizontal",
						mouseScrollNavigation: "off",
						onHoverStop: "off",
						arrows: {
					        enable: true,
					        style: 'gyges',
					        left: {
					            container: 'slider',
					            h_align: 'left',
					            v_align: 'center',
					            h_offset: 20,
					            v_offset: -60
					        },
					 
					        right: {
					            container: 'slider',
					            h_align: 'right',
					            v_align: 'center',
					            h_offset: 20,
					            v_offset: -60
					        }
					    },
						touch: {
							touchenabled: "on",
							swipe_threshold: 75,
							swipe_min_touches: 50,
							swipe_direction: "horizontal",
							drag_block_vertical: false
						},
						bullets: {
 
					        enable: false,
					        style: 'persephone',
					        tmp: '',
					        direction: 'horizontal',
					        rtl: false,
					 
					        container: 'slider',
					        h_align: 'center',
					        v_align: 'bottom',
					        h_offset: 0,
					        v_offset: 55,
					        space: 7,
					 
					        hide_onleave: false,
					        hide_onmobile: false,
					        hide_under: 0,
					        hide_over: 9999,
					        hide_delay: 200,
					        hide_delay_mobile: 1200
 						}
					},
					responsiveLevels: [1210, 1024, 778, 480],
					visibilityLevels: [1210, 1024, 778, 480],
					gridwidth: [1210, 1024, 778, 480],
					gridheight: [700, 700, 600, 600],
					lazyType: "none",
					parallax: {
						type: "scroll",
						origo: "slidercenter",
						speed: 1000,
						levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 46, 47, 48, 49, 50, 100, 55],
						type: "scroll",
					},
					shadow: 0,
					spinner: "off",
					stopLoop: "off",
					stopAfterLoops: -1,
					stopAtSlide: -1,
					shuffle: "off",
					autoHeight: "off",
					fullScreenAutoWidth: "off",
					fullScreenAlignForce: "off",
					fullScreenOffsetContainer: "",
					fullScreenOffset: "0px",
					disableProgressBar: "on",
					hideThumbsOnMobile: "off",
					hideSliderAtLimit: 0,
					hideCaptionAtLimit: 0,
					hideAllCaptionAtLilmit: 0,
					debugMode: false,
					fallbacks: {
						simplifyAll: "off",
						nextSlideOnWindowFocus: "off",
						disableFocusListener: false,
					}
				});
			}
		}); /*ready*/
	</script>	
 <script>

/*--Main Tiles Start--*/

var maintiles=$(".maintile")
$.get("index.php?getmaintiles",
(data)=>
	{
	var tilecontent=data.data;
	for(var i=0;i<maintiles.length;i++)
		{	if(tilecontent[i]!=null){
			$(maintiles[i]).children().children().text(tilecontent[i].maintile);
			$(maintiles[i]).css("display","block");
		}
			
			else
			{
				$(maintiles[i]).css("display","none");
				$(maintiles[i]).off("click",()=>{return false});
			}
		}
	});

	$(".course-post").on("click",".course-price a",(e)=>{
		console.log("download link clicked")
		$.ajax(
			{
			type:"post",
			url:"",
			data:{id:$(e.target).attr("data-id"),"action":"updatepd"}	
			})
		
		})

	$("#question_upload").on("submit",(e)=>{
		if($("[name='attachment']")[0].files[0]==null)
		{
			alert("Attach A file");
			return false;	
		}
		if($("[name='question']").val()=="")
		{
			alert("Enter Your Question	");
			return false;
		}
		
	})
</script>
	
</body>
</html>