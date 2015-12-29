<?php
require_once "php/config.php";
include "php/header.php";
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
<?php echo $header->Get_login(); ?>
  <body>
<div class="container">
  <div class="profile">
    <span class="profile__avatar" id="toggleProfile">
     <img src="img/user.jpg" alt="Avatar" /> 
    </span>
    <div class="profile__form">
      <div class="profile__fields">
        <div class="field">
          <input type="text" id="fieldUser" class="input" required pattern=.*\S.* />
          <label for="fieldUser" class="label">Username</label>
        </div>
        <div class="field">
          <input type="password" id="fieldPassword" class="input" required pattern=.*\S.* />
          <label for="fieldPassword" class="label">Password</label>
        </div>
          <div class="field" style="display:none;"><!--- google authenticator -->
              <input type="text" id="fieldPassword" class="input" required pattern=.*\S.* />
              <label for="fieldPassword" class="label">Password</label>
          </div>
        <div class="profile__footer">
          <button class="btn">Login</button>
		  <a href="forgot.php" class="forgot">Forgot Password ?</a><br />
		  <a href="reg.php" class="reg">Create an wallet</a>
        </div>
      </div>
     </div>
  </div>
</div>

    <script src="js/login.js"></script>
    
    
  </body>
</html>
