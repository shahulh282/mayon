<?PHP
use YoHang88\LetterAvatar\LetterAvatar;
include_once "config.php";
include "src/dbms.php";
include "src/User.php";
include "includes/functions.inc.php";
include "./vendor/autoload.php";


/*Registration Part*/
session_start();
if(isset($_POST['email']))
$_SESSION['email']=$_POST['email'];
//echo $_SESSION["email"];
if(isset($_POST['register']))
{
    header("content-type:application/json");
    $msg=register($_POST);
    $_SESSION["msg_type"]=isset($msg["info"])?$msg["info"]:NULL;
    $_SESSION["msg_text"]=isset($msg["message"])?$msg["message"]:NULL;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?PHP include "includes/student.head.inc.php";?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dhruv Math Physics</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/iofrm-theme6.css">


    <link rel="shortcut icon" href="images/logo.png">
    <!--  apple-touch-icon -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/logo.png">
    <link rel="apple-touch-icon-precomposed" href="assets/images/logo.png">
</head>


<body>
   <?PHP include "src/header.php";?>
    
    <div class="form-body">
        <div class="website-logo">
            <a href="index.php">
                <div class="logo">
                    <img class="logo-size" src="assets/images/logowht.png" alt="">
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="/assets/images/graphic2.svg" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Get more things done with Loggin platform.</h3>
                        <p>Access to the most powerfull tool in the entire design and web industry.</p>
                        <div class="page-links">
                            <a href="login.php">Login</a><a href="register.php" class="active">Register</a>
                        </div>
                        <form id='registeration' method="post" novalidate>
                            <div id='errmsg' style='display:none' class='alert alert-danger'></div>
                            <input class="form-control" type="text" name="name" placeholder="Full Name" required>
                            <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>


                                    <div class="form-group float-left" style="width: 28%;margin-top: -21px;margin-right: 5px;">
                                        <label for="exampleFormControlSelect1"></label>
                                        <select class="form-control" name="code" id="exampleFormControlSelect1">
                                          <option>+91</option>
                                          <option>+01</option>
                                          <option>+225</option>
                                        </select>
                                    </div> 
                                    <input style="width: 70%;" class="form-control float-left" type="text" name="number" placeholder="Whatsapp Number" required>


                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            <input class="form-control" type="password" name="re-password" placeholder="Confirm Password" required>

                            <div class="form-button">
                                <button id="submit" type="submit" name='register' value='true' class="ibtn">Register </button>
								
                            </div>
                        </form>
                        <div class="other-links">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
function otp_page()
{
return window.location.href="otp.php";	
}
//Validating Consts
const regemail=/^[a-z][a-z0-9.]+@[a-z0-9-.]+/;
const regnum=/[0-9]{10}|[a-z]/;
$("#registeration").on("submit",
(e)=>
{
e.preventDefault();
//return false;
var data=form_validate();
console.log(data);
    if(data==false)
        return false
	else
	$.ajax({
		url:"register.php",
		type:"post",
		data:data,
		dataType:'json',
		success:(data)=>{
			alert(data.message);
			if(data.info=='success')
			$.ajax(
			{
			url:"otp.php",
			type:"post",
			dataType:"json",
			data:{
				action:"send_otp"
				 },
			success:(data)=>
			{
			
			alert("Please Check Your Registered Email Id for otp");
			otp_page();
			
			},complete:(data)=>
			{
				
				console.log("Otp Request Sent");
			}
			}
			)
			
		},
		complete:(data)=>{},
		error:(data)=>{
			console.log("Error Occured");
		}
	})
})

function form_validate()
{
console.log("validating form");
    let name=$("[name='name']").val();
    let code=$("[name='code']").val();
    let number=$("[name='number']").val();
    let email=$("[type='email']").val();
    let pw=$("[name='password']").val();
    let rpw=$("[name='re-password']").val();
    
    if(name=="" || number=="" || email=="" || pw=="" || rpw=="")
    { alert("Please Fill Out All Fields");
        return false
    }
    if(regemail.test(email))
    {
        if(number.length<=8)
        {
            alert("Minimum Password Length is 8");
        }
        if(!regnum.test(number))
        {
        alert("Invalid Number");
        return false
        }
        if(/[0-9]/.test(pw) && /[a-z]/.test(pw) && /[A-Z]/.test(pw) && /[,>.>/?\'\";:\]=+-_!@#$%^&*()]/.test(pw))
        {
        if(pw==rpw)
            {
                
            }
            else
            {
               alert("Password Does not Match")     
            }
        }
    
    }
    else
    {
        alert("Invalid Email");
        return false;
    }
    
    let mobile=code+number;
    console.log("Validation Ended");
    return {name:name,mobile:mobile,email:email,password:pw,register:true};

}

</script>
</body>
</html>
<?PHP 

function register($arg)
{
    
    if (isset($arg["name"],$arg["email"],$arg["mobile"],$arg["password"]))
    {
        $arg["email"]=filter_var($arg["email"],FILTER_SANITIZE_EMAIL);
        $matches=NULL;
        echo preg_match("/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/",$arg["email"],$matches);

        
        
        //Step 1: Email Validation
        if(filter_var($arg["email"],FILTER_VALIDATE_EMAIL))
        {
            //Step 2: Number Validation
            
            if(num_validate($arg['mobile'])){
            

                if(password_validate($arg['password']))
                {
                    
                     if(!user_exists($arg['email'],$arg['mobile']))
                     {
                         
                        try{
                        
                        //Database Insertion
                        chdir(profiles);
                        $avatar=new LetterAvatar($arg['name'],"circle",256);
                        $avatar->saveAs($arg["email"].".png",LetterAvatar::MIME_TYPE_PNG);
                        $profilepath=str_replace("\\","/",realpath($arg["email"].".png"));
                        $sql=sprintf("  )",
                        $_POST["name"],$_POST["email"],$_POST["mobile"],hash('sha256',$_POST["password"]),$profilepath);
						$_SESSION["email"]=$_POST["email"];
                        $_SERVER["db"]->query($sql);
                        

                        return json_encode(array(("info")=>"success","message"=>"Registeration Successfull","insertid"=>$_SERVER["db"]->gconn()->insert_id));
                        }
                        catch(Exception $e){
                        //Registration Failed!
                            echo json_encode(array("info"=>"error","message"=>$e->getMessage()));
                            exit();
                        }
                     }
                     else
                        {
                            echo json_encode(array("info"=>"error","message"=>"User Already Exists Please Login!"));   
                            exit();
                        }
                        
                }
                else 
                    echo json_encode(array("info"=>"error","message"=>"Invalid Password"));
                    exit();
            }
            else
                echo json_encode(array("info"=>"error","message"=>"Invalid Number"));
                exit();
        }
        else 
            echo json_encode(array("info"=>"eror","message"=>"Invalid Email"));
            exit();

    
    


}

}

?>