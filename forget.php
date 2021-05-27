<?PHP
include "src/dbms.php";
include "src/mail_function.php";
include "includes/functions.inc.php";
session_start();


//Password Reset Procedure
//Validate and sanitize Email
if (isset($_POST["submit"], $_POST["username"]))
    if (!empty($_POST["username"])) {
        generatePasswordResetLink($_POST["username"]);
    }

if (isset($_GET["token"])) {
    $token = filter_var($_GET["token"], FILTER_SANITIZE_STRING);
    if (isset($token)) {
        $_SESSION["valid_token"] = true;
    } else {
        $_SESSION["valid_token"] = false;
    }
}


function generateLink($context)
{
    $data = json_decode($context);
    $str = "";
    foreach ($data as $key => $value) {
        if ($key != "time")
            $str .= $key . "=" . $value . "&";
    }
    $link = "http://localhost/dhruv/forgot.php?token={$str}";
    return substr($link, 0, strlen($link) - 1);
}

function randomHash($username)
{
    $token = hash("sha256", rand(1000000000000, 9999999999999));

    $_SERVER["db"]->query(sprintf(
        "INSERT INTO 
        password_reset 
        set email=\"%s\",expDate=%s,token=\"%s\" 
        ON DUPLICATE KEY UPDATE expDate=%s,token=\"%s\"",
        $username,
        $_SERVER["REQUEST_TIME"] + 120000,
        $token,
        $_SERVER["REQUEST_TIME"] + 120000,
        $token
    ));

    var_dump($_SERVER["db"]->gconn()->errno);
    return json_encode(
        array(
            "token" => $token,
            "email" => $username,
            "time" => $_SERVER["REQUEST_TIME"]
        )
    );
}

function generatePasswordResetLink($username)
{
    $username = filter_var($username, FILTER_SANITIZE_EMAIL);
    $username = filter_var($username, FILTER_VALIDATE_EMAIL);
    if (isset($username)) {
        $_SERVER["db"]->query(sprintf("select email from users where email=\"%s\"", $username));
        $data = $_SERVER["db"]->single(false);
        if ($data != null) {
            $link = generateLink(randomHash($data[0]));
            send_mail($username, "Password Reset link For MathsPhysics", $link, "");
            echo "<script>alert('Verification Link Sent')</script>";
        } else
            echo "<script>alert('Please Verify The Email You have entered')</script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dhruv Math Physics</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/iofrm-theme6.css">

    <link rel="shortcut icon" href="images/logo.png">
    <!--  apple-touch-icon -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/logo.png">
    <link rel="apple-touch-icon-precomposed" href="images/logo.png">
</head>

<body>
    <header class="clearfix">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php" style="margin:0;">
                    <img src="assets/images/logo.png" width="220px" class="loggimg" alt="">
                </a>
                <a href="#" class="mobile-nav-toggle">
                    <span></span>
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto pb-4">
                        <li class="drop-link mt-4">
                            <a class="" href="index.php">Home</a>
                        </li>
                        <li class="drop-link mt-4">
                            <a class="" href="about.php">About</a>
                        </li>
                        <?PHP if (isset($_SESSION['currentuser'])) { ?>
                            <li class="drop-link mt-4">
                                <a class="" href="courses.php">Course</a>
                            </li>
                            <li class="drop-link mt-4">
                                <a class="" href="communitypage.php">Community</a>
                            </li>
                            <li class="drop-link mt-4">
                                <a class="" href="tuition.php">Tuition</a>
                            </li>
                        <?PHP } ?>
                        <li class="drop-link mt-4">
                            <a href="contact.php">Contact</a>
                            <ul class="dropdown">
                                <li><a href="  https://wa.me/+918291687783"> <i class="fa fa-whatsapp mr-2"></i> +91 00000 00000</a></li>
                                <li><a href="mailto:mathphysics@gmail.com"> <i class="fa  fa-envelope mr-2"></i> mathphysics@gmail.com</a></li>
                            </ul>
                        </li>
                        <li style="margin-top: -15px;"><a href="login.php" class="register-modal-opener login-button" style="padding:11px 22px !important;margin-top: 15%;color:#fff !important;"><i class="material-icons" style="color:#fff !important;"></i> Login / Register</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="mobile-menu">
            <nav class="mobile-nav">
                <ul class="mobile-menu-list">
                    <li class="drop-link">
                        <a class="" href="../index.php">Home</a>
                    </li>
                    <li class="drop-link">
                        <a class="" href="../about.php">About</a>
                    </li>
                    <?PHP if (isset($_SESSION["currentuser"])) { ?>
                        <li class="drop-link">
                            <a class="" href="../courses.php">Course</a>
                        </li>
                        <li class="drop-link">
                            <a class="" href="../communitypage.php">Community</a>
                        </li>
                        <li class="drop-link">
                            <a class="" href="../tuition.php">Tuition</a>
                        </li>
                    <?PHP } ?>
                    <li class="drop-link">
                        <a href="../contact.php">Contact</a>
                        <ul class="dropdown">
                            <li><a href="  https://wa.me/+918291687783"> <i class="fa fa-whatsapp mr-2"></i> +91 00000 00000</a></li>
                            <li><a href="mailto:mathphysics@gmail.com"> <i class="fa  fa-envelope mr-2"></i> mathphysics@gmail.com</a></li>
                        </ul>
                    </li>
                    <li class="drop-link">
                        <a class="" href="login.php">Login / Register</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
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
                    <?PHP
                    // Password Reset Form
                    //Show the form if request for a valid token only 
                    if (isset($_GET['token']) && $_SESSION["valid_token"] == true) {
                    ?>
                        <div class='form-items'>
                            <h3>Change Password</h3>
                            <p>Create a secure Password to Protect Your self from security

                            </p>
                            <form action='forget.php' method='post'>
                                <input class="form-control" type="text" name="password" placeholder="New Password" required>
                                <input class="form-control" type="text" name="password" placeholder="Confirm Password" required>
                                <div class="form-button full-width">
                                    <button id="submit" name='action' type="changepassword" class="ibtn btn-forget">Change password</button>
                                </div>

                            </form>
                        </div>
                    <?PHP } else { ?>
                        <div class="form-items">
                            <h3>Password Reset</h3>
                            <p>To reset your password, enter the email address you use to sign in to get reset link</p>

                            <form action='forget.php' method='post'>
                                <input class="form-control" type="text" name="username" placeholder="E-mail Address" required>
                                <div class="form-button full-width">
                                    <button id="submit" name='submit' type="submit" class="ibtn btn-forget">Send Reset Link</button>
                                </div>

                            </form>
                        </div>
                    <?PHP } ?>
                    <?PHP if (isset($email)) { ?>
                        <div class="form-sent">
                            <div class="tick-holder">
                                <div class="tick-icon"></div>
                            </div>
                            <h3>Password link sent</h3>
                            <p>Please check your inbox <?PHP
                                                        echo $email;
                                                        ?></p>
                            <div class="info-holder">
                                <span>Unsure if that email address was correct?</span> <a href="#">We can help</a>.
                            </div>
                        </div>
                    <?PHP } else { ?>
                        <div class='form-sent'>
                            <div class='tick-holder'>
                                <div class='tick-icon'></div>
                            </div>
                            <h3>Password Changed Successfully</h3>
                            <p>click to <a href='login.php'>login</a></p>
                        </div>

                    <?PHP } ?>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!--<script src="assets/js/main.js"></script>-->
    <style>
        .form-sent.show {
            opacity: 1;
        }

        .form-sent.hide {
            opacity: 0;
        }
    </style>
    <script>
        $("#submit").on('click', () => {

        })
    </script>
</body>

</html>