<?PHP
include "src/dbms.php";

session_start();
if(isset($_POST["action"]))
switch($_POST["action"])
{

	case "send_otp":
	send_otp();
	break;
	case "verify_otp":
	header("content-type:application/json");
	verify_otp();
	exit();
	break;
	case "otp_status":
	header("content-type:application/json");
		if(isset($_SESSION["otp_status"]))
			echo json_encode(array("info"=>"success","Otp Status"=>$_SESSION["otp_status"]));
		else
			echo json_encode(array("info"=>"error","Otp Status"=>"Not Generated"));
	exit();
    break;
	case "expire":
	break;
default:exit();
}



function set_otp(){
    return rand(100000,999999);
}
function send_otp()
{
header("content-type:application/json");
// generate OTP
    unset($_SESSION["loggedinstatus"]);
    unset($_SESSION["otp"]);

    $_SESSION["time"]=$_SERVER["REQUEST_TIME"];
    $_SERVER["db"]->query("select vstatus from users where email=\"%s\"",$_SESSION['email']);
    
    if($_SERVER["db"]->data()==0){
        $_SESSION["otp"]=set_otp();
        $_SERVER["db"]->query(sprintf("UPDATE users set otp=\"%s\" where email=\"%s\"",$_SESSION['otp'],$_SESSION['email']));
            $_SESSION["otp_status"]="Generated";
            /*Otp Generated
            //Sending Otp to Mail Id via SMTP*/
			try{
            include "src/mail_function.php";
            send_mail($_SESSION["email"],"OTP","Your Otp Is ".$_SESSION["otp"]);
			echo json_encode(array("info"=>"success","message"=>"Otp Sent Successfully","session"=>json_encode($_SESSION)));
			}
			catch(Exception $e)
			{
			echo json_encode(array("info"=>"error","message"=>"Otp Sending Failed".$e->getMessage()));	
			}
            
        }
        else{
            echo "<script>alert('Verification Done Alredy U can login now')</script>";
        }
exit();
}

function verify_otp(){  
if($_SESSION["time"]-$_SERVER["REQUEST_TIME"]>300)
{
$_SESSION["otp_status"]	= "Expired";
$sql=sprintf("UPDATE users SET vstatus=0, otp= NULL WHERE email \"%s\"",$_SESSION["email"]);
$_SERVER["db"]->query($sql);
echo json_encode(array("info"=>"error","message","OTP Expired"));
}
if($_SESSION['otp']==$_POST['otp'])
{
    $_SESSION["otp_status"]="Verified";
    $sql=sprintf("UPDATE users SET vstatus=1 ,otp =NULL WHERE email=\"%s\"",$_SESSION['email']);
    $_SERVER['db']->query($sql);
    echo json_encode(array("info"=>"success","message"=>"Verified"));
}
else
echo json_encode(array("info"=>"error", "message"=>"Invalid Otp"));
}

function expiry_otp()
{
    $_SERVER["db"]->query(sprintf("UPDATE users set otp=\"%s\"",NULL));
    $_SESSION["otp_status"]="Expired";
    exit();
}

?>

<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dhruv Math Physics</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-theme6.css">

    <link rel="shortcut icon" href="images/logo.png">
    <!--  apple-touch-icon -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" href="images/logo.png">
</head>
<body>
<?PHP include "src/header.php";?>
    <div class="form-body">
        <div class="website-logo">
            <a href="index.php">
                <div class="logo">
                    <img class="logo-size" src="images/logowht.png" alt="">
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="images/graphic2.svg" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Enter OTP</h3>
                        <p>Enter the OTP sent to your email</p>
                        <form action='otp.php?verify' method='post'>
                        <div id='msg' style='display:none'></div>
                            <input class="form-control" type="" name="username" placeholder="OTP" required>
                            <div class="form-button full-width">
                                <button id="submit" type="submit" class="ibtn btn-forget">Verify</button>
                            </div>
                        </form>
                    </div>
                    <div class="form-sent">
                        <div class="tick-holder">
                            <div class="tick-icon"></div>
                        </div>
                        <h3>Account Verified</h3>
                        <p>Signin to your account</p>
                        <div class="info-holder">
                            <span></span> <a href="login.php" style="font-size: 24px;">Signin</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
function verify_otp(){
    let val=false
	$.ajax({
		url:"otp.php",
		type:"post",
		data:{"action":"verify_otp","otp":$("input[placeholder]").val()},
		success:(data)=>
    {
    data=JSON.parse(data);
    console.log(data);
    if(data.info=="success")
        {
        
        }
    
    }
	})
    
}
var time=60000;
var otptimer=setInterval(
    ()=>{
        time-=1000;
        $("#msg").css("display","block");
        $("#msg").text("You Have "+time/1000+"s");
        $.post("otp.php",{action:"otp_status"},(data)=>
		{if(data["Otp Status"]=="Verified")
			{
				clearInterval(otptimer);
				$("#msg").css("display","block");
                $("#msg").addClass("alert alert-success");
				$("#msg").text(data["Otp Status"]);
				$("div.form-items").attr("class","form-items hide-it");
				$("div.form-sent").attr("class","form sent show-it");
			}
            if(data.info=='success')
                {
                    $("#msg").css("display","block");
                    $("#msg").addClass("alert alert-success");
                }
            else{
                     $("#msg").addClass("alert alert-danger");
                }
            $("#msg").text(data.message);
        })
        if(time==0)
        {
        clearInterval(otptimer);
        }
        },1000);
</script>
</body>
</html>