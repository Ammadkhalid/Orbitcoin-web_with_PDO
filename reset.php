<?php
require_once "php/config.php";
include "php/header.php";
session_start();
if(isset($_GET["code"])) {
    $code = $_GET["code"];
    $chk = $con->prepare("SELECT Username FROM users WHERE Code=:code");
    $chk->execute(array(":code" => $code));
    if($chk->rowCount() != 1) {
        $title = "Invalid Password Recovery link!";
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <link rel="stylesheet" href="css/notifIt.css">
        <script src="js/notifIt.js"></script>
        <script>
            $(document).ready(function () {
                notif({
                    type: "info",
                    msg: "Invalid Link!",
                    autohide: true,
                    position: "bottom",
                    opacity: 0.7,
                    timeout: 5000,
                    zindex: 0,
                    offset: 0,
                    fade: 100,
                    bgcolor: "red"
                });
            });
        </script>
        <?php
    } else {
        $usr = $chk->fetch(PDO::FETCH_ASSOC);
        $title = $usr["Username"]." Reset Your Password";
    }
?>
<!DOCTYPE html>
<html >
<?php
echo $header->Get_reset($title);
?>
  <body>
<div class="container">
  <div class="profile">
    <span class="profile__avatar" id="toggleProfile">
     <img src="img/user.jpg" alt="Avatar" /> 
    </span>
    <div class="profile__form">
        <div class="profile__fields">
            <div class="field">
                <input type="password" id="fieldPassword" class="input" required pattern=.*\S.* />
                <label for="fieldUser" class="label">New Password</label>
            </div>
            <div class="field">
                <input type="password" id="fieldcPassword" class="input" required pattern=.*\S.* />
                <label for="fieldPassword" class="label">Confirm Password</label>
            </div>
            <div class="field">
                <input type="hidden" id="fieldUser" class="input" value="<?php echo $code;?>" required pattern=.*\S.* />
            </div>
            <div class="profile__footer">
                <button class="btn">Reset Password</button>
                <a href="login.php" class="forgot" style="display:none;">Login</a><br />
            </div>
        </div>
     </div>
  </div>
</div>
    <script src="js/reset.js"></script>
  </body>
</html>
<?php
} else {
    header("location:index.php");
}
?>