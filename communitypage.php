<?PHP
include "config.php";
include "src/dbms.php";
include "src/User.php";
include "src/post-module.php";
require "pager/Paginated.php";
require "pager/DoubleBarLayout.php";
require "pager/TrailingLayout.php";

session_start();
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
		$sql.=sprintf("SELECT count(pid) as count  from posts where lower(board)=\"%s\"",strtolower($term));
		$filter=1;
		
	}
	else if(preg_match("/tag:/i",$query)){
		$sql.=sprintf("SELECT count(pid) as count from posts where lower(subject)=\"%s\"",strtolower($term));
		$filter=2;
		
	}
	else if(isset($term) && isset($_GET["search"]))
	{
		$sql.=sprintf("SELECT count(pid) as count from posts where lower(class) %s UNION
					   SELECT count(pid) as count from posts where lower(subject) %s UNION
					   SELECT count(pid) as count from posts where lower(matric) %s UNION
					   SELECT count(pid) as count from posts where lower(ptitle) %s UNION
					   SELECT count(pid) as count from posts where lower(pcontent) %s ",regorbuilder($term),
					   regorbuilder($term),regorbuilder($term),regorbuilder($term),regorbuilder($term));
					   //echo $sql;		
					   $filter=3;
	}
	else
	{
		$sql.=sprintf("SELECT count(pid) as count from posts");
		//echo "Triggerd Normal";
		$filter=4;
	}
	$_SERVER["db"]->query($sql);
	//echo $sql;
	$total_records=$_SERVER["db"]->data();

	switch($filter)
	{
		case 1:
			$_SERVER["db"]->query(sprintf("select pid from posts where board =\"%s\" order by pid limit %s,6",$term,($page-1)*6));
			break;
		case 2:
			$_SERVER["db"]->query(sprintf("select pid from posts where subject= \"%s\" order by pid limit %s,6",$term,($page-1)*6));
			break;
		case 3:
			$_SERVER["db"]->query(sprintf("
			select pid from posts where matric %s UNION
			select pid from posts where subject %s UNION
			select pid from posts where ptitle %s UNION
			select pid from posts where pcontent %s order by pid limit %s,6",regorbuilder($term),
		regorbuilder($term),regorbuilder($term),regorbuilder($term),($page-1)*6));
			break;
		default:
			$_SERVER["db"]->query(sprintf("select pid from posts order by ptimestamp limit %s,6",($page-1)*6));
			break;
	}
$data=array
(
"idset"=>$_SERVER["db"]	->recordsArray(false),
"page"=>$page,
"iTotalRecords"=>$total_records,
"total_pages"=>ceil($total_records/6)
);
return $data;
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
		<?PHP include "src/header.php";?>
		<!-- page-banner-section 
			================================================== -->
		<section class="page-banner-section">
			<div class="container">
				<h1>Home</h1>
				<ul class="page-depth">
					<li><a href="index.php">Home</a></li>
					<li><a href="communitypage.php">Community </a></li>
				</ul>
				<div class="title-section">
					<div class="left-part">

					</div>
					<div class="right-part">
						<a class="button-one" href="">Ask a Question</a>
					</div>
				</div>
			</div>
		</section>
		<!-- End page-banner-section -->

		<!-- blog-section 
			================================================== -->
		<section class="blog-section">
			<div class="container">
				<div class="row">

					<div class="col-lg-4">
						<div class="sidebar">
							<div class="search-widget widget">
								<form class="search-form" action='communitypage.php'>
									<input type="search" name='search' class="search-field" placeholder="Enter keyword...">
									<button type="submit" name='action' class="search-submit">
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
										$sql=sprintf("SELECT DISTINCT UPPER (category) as category from category");
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
								function tag_list()
								{
									$sql=sprintf("SELECT DISTINCT subject from posts");
									$_SERVER["db"]->query($sql);
									$taglist=$_SERVER["db"]->recordsArray(false);
									foreach($taglist as $row)
										echo "<li><a href=\"communitypage.php?search=tag:".$row["subject"]."\">".$row["subject"]."</a></li>";
								}
									tag_list();
								?>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-8">

						<div class="blog-box">
								<?PHP 
								$limit=count($resultset["idset"])>6?6:count($resultset["idset"]);
								$pagedResults=new Paginated($resultset["idset"],$limit,$page);
								if(is_array($resultset) && count($resultset)>0)
								while($row =$pagedResults->fetchPagedRow())
								{
									echo post_template($row["pid"],2);
								}
								else
								echo "<h2>NO Records found</h2>";
								
								$pagedResults->setLayout(new DhruvLayout());
								echo "<ul class='page-pagination'>";
								echo $pagedResults->fetchPagedNavigation("",isset($_GET["search"])?$_GET["search"]:"");
								echo "</ul>";
								?>

						</div>
					</div>

				</div>
						
			</div>
		</section>

		<!-- End blog section -->

		<!--new Enquiry Model-->
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
		<?PHP
		include "src/footer.html";
		?>

	</div>
	<!-- End Container -->

	
	<script src="assets/js/studiare-plugins.min.js"></script>
	<script src="assets/js/jquery.countTo.js"></script>
	<script src="assets/js/popper.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en"></script>
	<script src="assets/js/gmap3.min.js"></script>
	<script src="assets/js/script.js"></script>
	<script>
								$(".search-form").on("submit",(e)=>{
									e.preventDefault();
									console.log("searchlick");
									window.location.href="communitypage.php?search="+$(".search-form input[type='search']").val()+"&page=1";
								})
								$(".button-one").on("click",(e)=>{
									e.preventDefault();
									$('#enquirypopup').modal('show');
								})
								</script>
	
</body>
</html>
<?PHP 

function fetch_all_catagories()
{	
	$sql=sprintf("SELECT DISTINCT pcategory from posts");
	$_SERVER["db"]->query($sql);
	$cat=$_SERVER['db']->recordsArray(false);
	forEach($cat as $c)
		echo "<li><a href=\"communitypage.php?search=category:".$c["pcategory"]."\">".$c["pcategory"]."</a></li>";
}
function fetch_all_tags(){

	$sql=sprintf("SELECT DISTINCT ptags from posts");
	$_SERVER["db"]->query($sql);
	$tag=$_SERVER["db"]->recordsArray(false);
	forEach($tag as $t)
		echo "<li><a href=\"communitypage.php?search=category:".$t["ptags"]."\">".$t["ptags"]."</a></li>";
}
?>