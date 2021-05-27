<?PHP

try {
    $db = mysqli_connect("localhost", "root", "", "dhruv", 3306, "http");
} catch (Exception $e) {
    //echo "Database Not Connected";
}

//File System
//FILE LOCATION CONFIGURATION
//CAUTION: PLEASE DON'T EDIT THIS PART THIS WILL COLLAPSE THE PROJECT
define("FS", $_SERVER["DOCUMENT_ROOT"]);
define("COURSES", realpath("./upload/courses"));
define("QUESTIONS", realpath("./upload/Questions"));
define("POPULAR", realpath("./upload/PopularDownloads"));
define("profiles",realpath("./upload/profiles"));

/*
Uncomment Below Code to create or reinitialize the project folders
$fc=0;
foreach(array(COURSES,QUESTIONS,POPULAR) as $folder){
        if(!file_exists($folder))
            {
                mkdir($folder);
                $fc++;
            }
            else
            {

            }
    }   
echo $fc;
*/
//SMTP SERVER CONFIGURAION CONSTANT
//Comment out the development credentials on deploying

//Development
define("SMTP_HOST", "localhost");
define("SMTP_USERNAME", "hariharan@localhost.com");
define("SMTP_PASSWORD", "Hariaruna24");
define("SMTP_PORT", 25);

//Production
//define("SMTP_HOST","");
//define("SMTP_USERNAME","");
//define("SMTP_PASSWORD","");
//define("SMTP_PORT",25);

?>