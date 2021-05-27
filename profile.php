<?PHP
include "config.php";
include "src/dbms.php";
include "src/User.php";
include "includes/access.inc.php";
include "includes/functions.inc.php";
$detail = $_SESSION["currentuser"]->getDetails()[0];
$profile = $detail['profile'];

$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if (isset($_POST) && $_POST['action'] == 'updatedetails') {
    $errmsg="";
    $count = 0;
    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $count++;
            $errmsg.="Please Fill Out $key </br>";
        }
    }
   /* echo $count;*/
    if ($count != 0) {
    }
}
?>

<!Doctype html>
<html>
<?PHP include "includes/student.head.inc.php"; ?>

<body>
    <?PHP include "src/header.php"; ?>
    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class='col-lg-4'>
                    <div class='card'>
                        <div>
                            <h1>Change Profile Picture</h1>
                            <div style='margin:auto;'>
                                <img class='' src="<?PHP echo "/upload/profiles/" . basename($profile); ?>" style='' />
                            </div>
                            <form id='profilepic' action="profile.php" method='post' enctype='multipart/form-data'>
                                <input id='pp' type='file' accept='image/png,image/png' required />
                                <button type='submit' name='action' value='updateProfile'>Update</button>
                            </form>
                        </div>
                    </div>
                    <div class='card'>
                        <div>
                            <?PHP foreach($detail as $key=>$value){
                            if($key=="profile"){   
                            ?>
                                <h6 class="card-category text-gray"><?PHP echo $key;?></h6>
                            <h4 class="card-title"><?PHP echo $value; ?></h4>
                            <?PHP }}?>
                            
                            <h6 class="card-category text-gray">Email</h6>
                            <h4 class="card-title"><?PHP echo $detail["email"]; ?></h4>
                            <a href="javascript:;" class="btn btn-primary btn-round">Follow</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <?PHP
                    if (isset($_POST["action"]) && $_POST["action"] === "updatedetails") {
                        if (isset($_SESSION["message"], $_SESSION["msgtype"]))
                            message($_SESSION["message"], "success");
                        elseif (isset($_SESSION["message"]))
                            message($_SESSION["message"]);
                    }
                    ?>
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Edit Profile</h4>
                            <p class="card-category">Complete your profile</p>
                         
                        </div>

                        <div class="card-body">
                            <form action='profile.php' method='post'>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Email address</label>
                                            <input type="email" name='email' class="form-control" value='<?PHP echo $detail['email']; ?>'>
                                        </div> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Name</label>
                                            <input type="text" class="form-control" name='name' value="<?PHP echo $detail['name']; ?>">

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Address</label>
                                            <input type="text" name='address' class="form-control" value="<?PHP echo $detail['address']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">City</label>
                                            <input type="text" name='city' class="form-control" value="<?PHP echo $detail['city']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Country</label>
                                            <input type="text" name='country' class="form-control" value="<?PHP echo $detail['country']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Postal Code</label>
                                            <input type="text" name='zipcode' class="form-control" value="<?PHP echo $detail['zipcode']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name='action' value='updatedetails' class="btn btn-primary pull-right">Update Profile</button>
                                <div class="clearfix"></div>
                            </form>

                        </div>

                        <div class='col-lg-12'>
                            <div class='card'>
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Change Password</h4>
                                    <p class="card-category">Complete your profile</p>
                                </div>
                                <div class='' style='padding:30px;'>
                                    <form>
                                        <input type='text' placeholder="Old Password" />
                                        <input type='text' placeholder='New Password' />
                                        <input type='text' placeholder='Confirm New Password' />
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?PHP

            include "src/footer.html";
            include "includes/student.scripts.inc.php";

            ?>
            <script>
                var form = null;
                var http = null;

                form = document.querySelector("#profilepic");
                var frmdata = new FormData();
                frmdata.append("profilepic", document.querySelector("#pp[type='file']").files[0]);
                http = new XMLHttpRequest();
                http.open("post", "profile.php", true);
                http.onreadystatechange = () => {
                    if (http.status == 200)
                        console.log("Response Success");
                    if (http.status == 404)
                        console.log("Response Error");
                }
                http.upload.addEventListener("progress", (e) => {
                    let percent_complete = (e.loaded / e.total) * 100;
                })
                form.addEventListener("submit", (e) => {
                    e.preventDefault();
                    alert("form submitted Event Captured");
                    http.send({
                        data: frmdata
                    })
                });
                var http = new XMLHttpRequest();
                http.onreadystatechange = () => {
                    if (http.status == "200") {
                        console.log("Request Sent");
                    }
                    if (http.status == '404') {
                        console.log("Request Failed");
                    }
                    if (http.status == '201') {
                        console.log("Preparing Result");
                    }
                }
                http.open("GET", "Controller/ProfileController.php");
                http.send();
                /*$("").on("submit",(e)=>{
                    e.preventDefault();
                    alert("Form Submitted");
                });*/
            </script>
</body>

</html>