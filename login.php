<?PHP 
include "src/dbms.php";
require_once "src/User.php";
session_start();

if(isset($_GET["logout"])){
    User::logout();
    header("location:login.php");
}


if(isset($_POST["login"]))
{
    User::login($_POST['username'],$_POST['password']);
    if(isset($_SESSION["currentuser"]))
    if($_SESSION["currentuser"]!=NULL)
    if($_SESSION['currentuser']->getRole()==0)
    {
            header("location:Adminpanel");
    }
    else
        {
            header("location:index.php");

        }
}


?>





<!DOCTYPE html>
<html lang="en">
<head>
<?PHP include "./includes/student.head.inc.php";?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dhruv Math Physics</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/iofrm-theme6.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fonts/iconfont/material-icons.css" media="screen">
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
                    <img class="logo-size" src="assets/images/logowht.png" alt="">
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="assets/images/graphic2.svg" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Get more things done with Loggin platform.</h3>
                        <p>Access to the most powerfull tool in the entire design and web industry.</p>
                        <div class="page-links">
                            <a href="login.php" class="active">Login</a><a href="register.php">Register</a>
                        </div>
                        <form action='login.php' method='POST' >
                            <input type='hidden' name='redirect' value=<?PHP echo isset($_GET["redirect"])?$_GET["redirect"]:"";?>/>
                            <input class="form-control" type="text" name="username" placeholder="E-mail Address" required>
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            <div class="form-button">
                                <button id="submit"  name='login' type="submit" class="ibtn" style="color: #000;"> Login</button> <a href="forget.php">Forget password?</a>
                            </div>
                        </form>
                        <div class="other-links">
                            <span>Or Login with</span><a href="#">Facebook</a><a href="#">Google</a><a href="#">Linkedin</a>
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
</body>
</html>