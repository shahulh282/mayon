<?PHP
if(!isset($_SESSION["currentuser"]))
{
   header("location:../login.php");
}

class Token{
   private $csrfToken;
   

   function __construct()
   {
      $this->csrfToken=hash('sha-1',random_bytes(16));

   }
   static function generateCSRF()
   {

   }
   static function verifyCSRF(){}
}
?>