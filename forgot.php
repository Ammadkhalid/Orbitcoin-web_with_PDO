<?php
include "php/header.php";
require_once "php/config.php";
session_start();
if(isset($_SESSION["Usr_d"])) {
    $chk_session = $_SESSION["Usr_d"];
    $prepare = $con->prepare("SELECT Session_id FROM users WHERE Session_id=:id");
    $prepare->execute(array(":id" => $chk_session));
    if($prepare->rowCount() == 1) {
        header("location:wallet.php");
    } else {
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html >
<?php echo $header->Get_forgot(); ?>
  <body>
<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<div class="container">
  <div class="profile">
    <span class="profile__avatar" id="toggleProfile">
     <img src="img/user.jpg" alt="Avatar" />
    </span>
    <div class="profile__form">
      <div class="profile__fields">
        <div class="field">
          <input type="text" id="fieldUser" class="input" required pattern=.*\S.* />
          <label for="fieldUser" class="label">Email</label>
        </div>
        <div class="field">
          <input type="text" id="fieldPassword" class="input" required pattern=.*\S.* />
          <label for="fieldPassword" class="label">Username</label>
        </div>
        <div class="profile__footer">
          <button class="btn">Reset Password</button>
		  <a href="login.php" class="forgot">Login</a><br />
        </div>
      </div>
     </div>
  </div>
</div>

    <script src="js/forgot.js"></script>
    
    
  </body>
</html>
