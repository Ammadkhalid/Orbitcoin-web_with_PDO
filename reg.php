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
<?php echo $header->Get_reg(); ?>
  <body>
<div class="container">
  <div class="profile">
    <span class="profile__avatar" id="toggleProfile">
     <h3>Create Wallet For <span style="color:#478B16">Orb</span></h3>
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
		<div class="field">
          <input type="password" id="fieldCPassword" class="input" required pattern=.*\S.* />
          <label for="fieldCPassword" class="label">Confirm Password</label>
        </div>
		<div class="field">
          <input type="text" id="fieldEmail" class="input" required pattern=.*\S.* />
          <label for="fieldEmail" class="label">Email</label>
        </div>
		<div class="field">
		<!--<input type="hidden" name="capcode" id="capcode" value="false" />
		//<?php
		//if (!class_exists('KeyCAPTCHA_CLASS')) {
		////	include('php/keycaptcha.php');
		//}
		///$kc_o = new KeyCAPTCHA_CLASS();
		//echo $kc_o->render_js();
		//
		///?>
		</div> ---->
        <div class="profile__footer">
          <button class="btn">Create Wallet</button><br />
		  <a href="login.php" class="reg">Login Now?</a>
        </div>
      </div>
     </div>
  </div>
</div>
  <script src="js/reg.js"></script>
  </body>
</html>